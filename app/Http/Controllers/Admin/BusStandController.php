<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusStand;
use App\Models\Terminal;
use App\Models\User;
use App\Services\BusStand\BusStandOnboardingService;
use App\Services\City\CityService;
use App\Services\Route\RouteService;
use App\Services\Terminal\BusStandAssignmentService;
use App\Services\Terminal\TerminalUserService;
use App\Services\Terminal\TerminalService;
use App\Traits\BelongsToBusStand;
use App\Traits\BelongsToTerminal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusStandController extends Controller
{
    use BelongsToBusStand, BelongsToTerminal;

    public function __construct(
        private BusStandOnboardingService $onboardingService,
        private CityService $cityService,
        private TerminalService $terminalService,
        private BusStandAssignmentService $assignmentService,
        private TerminalUserService $terminalUserService,
        private RouteService $routeService,
    ) {}

    public function index(): View
    {
        $query = BusStand::with([
            'owner',
            'terminal',
            'assignedUsers',
            'routes' => fn ($q) => $q->where('is_active', true)->orderBy('name'),
        ])->latest();

        if (auth()->user()->isTerminalAdmin()) {
            $query->whereIn('terminal_id', auth()->user()->ownedTerminals()->pluck('id'));
        }

        $stands = $query->paginate(15);

        return view('admin.bus-stands.index', compact('stands'));
    }

    public function myStand(): View|RedirectResponse
    {
        $stands = auth()->user()->assignedBusStands()
            ->with('terminal')
            ->withCount([
                'vehicles',
                'routes',
                'routes as active_routes_count' => fn ($q) => $q->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();

        abort_unless($stands->isNotEmpty(), 404, 'No bus stand assigned to your account. Contact your Terminal Admin.');

        if ($stands->count() === 1) {
            return redirect()->route('admin.bus-stands.edit', $stands->first());
        }

        $summary = [
            'stands' => $stands->count(),
            'vehicles' => $stands->sum('vehicles_count'),
            'routes' => $stands->sum('active_routes_count'),
        ];

        return view('admin.bus-stands.my-list', compact('stands', 'summary'));
    }

    public function create(): View
    {
        $user = auth()->user();
        $terminals = $this->terminalService->selectableFor($user);

        if ($terminals->isEmpty()) {
            return view('admin.bus-stands.create', [
                'noTerminals' => true,
                'terminals' => $terminals,
            ]);
        }

        $defaultTerminal = $user->isTerminalAdmin()
            ? $user->primaryTerminal()
            : $terminals->first();

        $usersByTerminal = $terminals->mapWithKeys(fn ($t) => [
            $t->id => $this->terminalUsersForForm($t->id, $user)
                ->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])
                ->values(),
        ]);

        return view('admin.bus-stands.create', [
            'noTerminals' => false,
            'terminals' => $terminals,
            'terminalsMap' => $terminals->mapWithKeys(fn ($t) => [$t->id => $t->city]),
            'usersByTerminal' => $usersByTerminal,
            'cities' => $this->cityService->activeNames()->values(),
            'isTerminalAdmin' => $user->isTerminalAdmin(),
            'defaultFromCity' => $defaultTerminal->city,
            'defaultTerminalId' => $defaultTerminal->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'terminal_id' => $this->terminalService->idValidationRulesFor($user),
            'name' => 'required|string|max:255',
            'type' => 'required|in:company,individual',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
            'routes' => 'nullable|array',
            'routes.*.departure_city' => $this->cityService->nameValidationRules(false),
            'routes.*.destination_city' => $this->cityService->nameValidationRules(false),
            'routes.*.base_fare' => 'nullable|numeric|min:0',
            'routes.*.distance_km' => 'nullable|numeric|min:0',
            'routes.*.duration_minutes' => 'nullable|integer|min:0',
        ]);

        foreach ($validated['routes'] ?? [] as $index => $route) {
            if (empty($route['destination_city'])) {
                continue;
            }

            $request->validate([
                "routes.{$index}.base_fare" => 'required|numeric|min:0',
                "routes.{$index}.destination_city" => $this->cityService->nameValidationRules(),
            ]);
        }

        if ($user->isTerminalAdmin()) {
            abort_unless($user->ownsTerminal($validated['terminal_id']), 403);
        }

        $standData = $this->buildStandData($validated);

        $stand = $this->onboardingService->createStandOnly($standData);

        $routeCount = $this->routeService->createManyForStand(
            $stand,
            $validated['routes'] ?? []
        );

        $assigned = 0;
        foreach ($validated['user_ids'] ?? [] as $userId) {
            $assignee = User::query()->findOrFail($userId);
            abort_unless(
                $assignee->isBusStandAdmin() && $assignee->terminal_id === (int) $validated['terminal_id'],
                403
            );
            $this->assignmentService->assignStandToUser($stand, $assignee);
            $assigned++;
        }

        $message = collect([
            'Bus stand created.',
            $routeCount > 0 ? "{$routeCount} route(s) added." : null,
            $assigned > 0 ? "{$assigned} user(s) assigned." : null,
        ])->filter()->implode(' ');

        return redirect()
            ->route('admin.bus-stands.edit', $stand)
            ->with('success', $message);
    }

    public function edit(BusStand $busStand): View
    {
        $this->authorizeBusStandOwner($busStand);
        $busStand->load([
            'owner',
            'terminal',
            'assignedUsers',
            'routes' => fn ($q) => $q->orderBy('departure_city')->orderBy('destination_city'),
        ]);
        $busStand->loadCount([
            'vehicles',
            'routes',
            'routes as active_routes_count' => fn ($q) => $q->where('is_active', true),
        ]);

        $actor = auth()->user();

        $terminals = $actor->isBusStandAdmin()
            ? collect()
            : $this->terminalService->selectableFor($actor);

        $usersByTerminal = $terminals->mapWithKeys(fn ($t) => [
            $t->id => $this->terminalUsersForForm($t->id, $actor)
                ->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])
                ->values(),
        ]);

        $assignedUserIds = $busStand->assignedUsers->pluck('id')->all();
        if ($busStand->owner_id && ! in_array($busStand->owner_id, $assignedUserIds, true)) {
            $assignedUserIds[] = $busStand->owner_id;
        }

        return view('admin.bus-stands.edit', [
            'busStand' => $busStand,
            'terminals' => $terminals,
            'terminalsMap' => $terminals->mapWithKeys(fn ($t) => [$t->id => $t->city]),
            'usersByTerminal' => $usersByTerminal,
            'assignedUserIds' => array_map('intval', $assignedUserIds),
            'isTerminalAdmin' => $actor->isTerminalAdmin(),
        ]);
    }

    public function update(Request $request, BusStand $busStand): RedirectResponse
    {
        $this->authorizeBusStandOwner($busStand);
        $busStand->load('owner');

        $user = auth()->user();

        $rules = [
            'type' => 'required|in:company,individual',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ];

        $rules['name'] = 'required|string|max:255';

        if ($user->isSuperAdmin() || $user->isTerminalAdmin()) {
            $rules['terminal_id'] = $this->terminalService->idValidationRulesFor($user);
            $rules['user_ids'] = 'nullable|array';
            $rules['user_ids.*'] = 'integer|exists:users,id';
        }

        // Owner login fields only appear on the bus-stand-admin edit form.
        $canEditOwner = $user->isBusStandAdmin() && $user->ownsBusStand($busStand->id);

        if ($canEditOwner && $busStand->owner) {
            $rules['owner_name'] = 'required|string|max:255';
            $rules['owner_email'] = 'required|email|max:255|unique:users,email,'.$busStand->owner_id;
            $rules['owner_phone'] = 'nullable|string|max:20';
            $rules['owner_password'] = 'nullable|string|min:8|confirmed';
        }

        $routeRules = [
            'routes' => 'nullable|array',
            'routes.*.departure_city' => $this->cityService->nameValidationRules(),
            'routes.*.destination_city' => $this->cityService->nameValidationRules(),
            'routes.*.base_fare' => 'required|numeric|min:0',
            'routes.*.distance_km' => 'nullable|numeric',
            'routes.*.duration_minutes' => 'nullable|integer',
            'routes.*.is_active' => 'boolean',
        ];

        $validated = $request->validate(array_merge($rules, $routeRules));

        if ($user->isBusStandAdmin()) {
            $busStand->update([
                'name' => trim($validated['name']),
                'type' => $validated['type'],
                'address' => $validated['address'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
            ]);
        } else {
            $terminalId = $validated['terminal_id'];
            if ($user->isTerminalAdmin()) {
                abort_unless($user->ownsTerminal($terminalId), 403);
            }

            $terminal = Terminal::findOrFail($terminalId);

            $busStand->update([
                'name' => trim($validated['name']),
                'type' => $validated['type'],
                'address' => $validated['address'],
                'city' => $terminal->city,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'terminal_id' => $terminalId,
            ]);

            $this->assignmentService->syncUsersForStand(
                $busStand->fresh(),
                $validated['user_ids'] ?? []
            );
        }

        if ($canEditOwner && $busStand->owner) {
            $this->onboardingService->updateOwner($busStand->owner, [
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'phone' => $validated['owner_phone'] ?? null,
                'password' => $validated['owner_password'] ?? null,
            ]);
        }

        if (! empty($validated['routes'])) {
            $this->routeService->syncRoutesForStand($busStand, $validated['routes']);
        }

        $redirect = match (true) {
            $user->isBusStandAdmin() => route('admin.bus-stands.edit', $busStand),
            default => route('admin.bus-stands.edit', $busStand),
        };

        return redirect($redirect)->with('success', 'Bus stand and routes updated.');
    }

    public function destroy(BusStand $busStand): RedirectResponse
    {
        $this->authorizeBusStandOwner($busStand);

        if ($busStand->owner) {
            $busStand->owner->update(['is_active' => false]);
        }

        $busStand->delete();

        return redirect()->route('admin.bus-stands.index')->with('success', 'Bus stand removed.');
    }

    private function terminalUsersForForm(int $terminalId, User $actor)
    {
        if ($actor->isBusStandAdmin()) {
            return collect();
        }

        return User::query()
            ->role('admin')
            ->where('terminal_id', $terminalId)
            ->orderBy('name')
            ->get();
    }

    private function buildStandData(array $validated): array
    {
        $terminal = Terminal::findOrFail($validated['terminal_id']);

        return [
            'terminal_id' => $terminal->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'address' => $validated['address'],
            'city' => $terminal->city,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ];
    }
}
