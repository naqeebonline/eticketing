<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusStand;
use App\Models\Route;
use App\Services\City\CityService;
use App\Services\Route\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function __construct(
        private CityService $cityService,
        private RouteService $routeService,
    ) {}

    public function index(Request $request): View
    {
        $query = $this->routeService->queryForUser(auth()->user());

        if ($request->filled('bus_stand_id')) {
            $standId = (int) $request->bus_stand_id;
            $this->routeService->assertStandAccess(auth()->user(), $standId);
            $query->where('bus_stand_id', $standId);
        }

        $routes = $query->paginate(15)->withQueryString();

        $busStands = $this->routeService
            ->selectableStandsFor(auth()->user())
            ->orderBy('name')
            ->get();

        return view('admin.routes.index', compact('routes', 'busStands'));
    }

    public function create(Request $request): View
    {
        $busStands = $this->routeService
            ->selectableStandsFor(auth()->user())
            ->get();

        $selectedStandId = (int) ($request->query('bus_stand_id') ?: old('bus_stand_id', $busStands->count() === 1 ? $busStands->first()?->id : 0));

        if ($selectedStandId) {
            $this->routeService->assertStandAccess(auth()->user(), $selectedStandId);
        }

        $selectedStand = $busStands->firstWhere('id', $selectedStandId);

        return view('admin.routes.create', [
            'busStands' => $busStands,
            'selectedStandId' => $selectedStandId ?: null,
            'defaultDepartureCity' => $selectedStand?->terminal?->city ?? $selectedStand?->city,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bus_stand_id' => 'required|exists:bus_stands,id',
            'departure_city' => $this->cityService->nameValidationRules(),
            'destination_city' => $this->cityService->nameValidationRules(),
            'distance_km' => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
            'base_fare' => 'required|numeric|min:0',
            'stops' => 'nullable|array',
            'stops.*.name' => 'required_with:stops|string',
        ]);

        $stand = BusStand::with('terminal')->findOrFail($data['bus_stand_id']);
        $this->routeService->assertStandAccess(auth()->user(), $stand->id);

        abort_if(
            $data['departure_city'] === $data['destination_city'],
            422,
            'Departure and destination must be different cities.'
        );

        $data['name'] = $this->routeService->buildRouteName(
            $data['departure_city'],
            $data['destination_city'],
        );

        $route = $this->routeService->createForStand($stand, $data);

        if (! empty($data['stops'])) {
            foreach ($data['stops'] as $index => $stop) {
                $route->stops()->create([
                    'name' => $stop['name'],
                    'city' => $stop['city'] ?? null,
                    'order' => $index + 1,
                    'arrival_offset_minutes' => $stop['arrival_offset_minutes'] ?? 0,
                ]);
            }
        }

        return redirect()
            ->route('admin.routes.index', ['bus_stand_id' => $stand->id])
            ->with('success', "Route created for {$stand->displayTitle()}.");
    }

    public function edit(Route $route): View
    {
        $route->load('busStand.terminal');
        $this->routeService->assertStandAccess(auth()->user(), $route->bus_stand_id);

        return view('admin.routes.edit', [
            'route' => $route,
            'defaultDepartureCity' => $route->departure_city,
        ]);
    }

    public function update(Request $request, Route $route): RedirectResponse
    {
        $route->load('busStand');
        $this->routeService->assertStandAccess(auth()->user(), $route->bus_stand_id);

        $data = $request->validate([
            'departure_city' => $this->cityService->nameValidationRules(),
            'destination_city' => $this->cityService->nameValidationRules(),
            'distance_km' => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
            'base_fare' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $this->routeService->updateRoute($route, [
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.bus-stands.edit', $route->busStand)
            ->with('success', 'Route updated.');
    }
}
