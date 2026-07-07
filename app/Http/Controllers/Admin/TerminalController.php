<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use App\Services\City\CityService;
use App\Services\Terminal\TerminalOnboardingService;
use App\Traits\BelongsToTerminal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TerminalController extends Controller
{
    use BelongsToTerminal;

    public function __construct(
        private CityService $cityService,
        private TerminalOnboardingService $onboardingService,
    ) {}

    public function index(): View
    {
        $terminals = Terminal::query()
            ->with('owner')
            ->withCount('busStands')
            ->ordered()
            ->paginate(20);

        return view('admin.terminals.index', compact('terminals'));
    }

    public function create(): View
    {
        return view('admin.terminals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => $this->cityService->nameValidationRules(),
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'owner_phone' => 'nullable|string|max:20',
            'owner_password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->onboardingService->createWithOwner(
            [
                'name' => $validated['name'],
                'city' => $validated['city'],
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $validated['sort_order'] ?? 0,
            ],
            [
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'phone' => $validated['owner_phone'] ?? null,
                'password' => $validated['owner_password'],
            ]
        );

        return redirect()
            ->route('admin.terminals.index')
            ->with('success', "Terminal created. Login for Terminal Admin: {$result['owner']->email}");
    }

    public function myTerminal(): RedirectResponse
    {
        $terminal = auth()->user()->primaryTerminal();

        abort_unless($terminal, 404, 'No terminal is linked to your account. Contact Super Admin.');

        return redirect()->route('admin.terminals.edit', $terminal);
    }

    public function edit(Terminal $terminal): View
    {
        $this->authorizeTerminalOwner($terminal);
        $terminal->load('owner');

        return view('admin.terminals.edit', compact('terminal'));
    }

    public function update(Request $request, Terminal $terminal): RedirectResponse
    {
        $this->authorizeTerminalOwner($terminal);
        $terminal->load('owner');

        $rules = [
            'name' => 'required|string|max:255',
            'city' => $this->cityService->nameValidationRules(),
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];

        $canEditOwner = auth()->user()->isSuperAdmin()
            || (auth()->user()->isTerminalAdmin() && $terminal->owner_id === auth()->id());

        if ($canEditOwner && $terminal->owner) {
            $rules['owner_name'] = 'required|string|max:255';
            $rules['owner_email'] = 'required|email|max:255|unique:users,email,'.$terminal->owner_id;
            $rules['owner_phone'] = 'nullable|string|max:20';
            $rules['owner_password'] = 'nullable|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        $terminal->update([
            'name' => trim($validated['name']),
            'city' => $validated['city'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'is_active' => auth()->user()->isSuperAdmin() ? $request->boolean('is_active') : $terminal->is_active,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if ($canEditOwner && $terminal->owner) {
            $this->onboardingService->updateOwner($terminal->owner, [
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'phone' => $validated['owner_phone'] ?? null,
                'password' => $validated['owner_password'] ?? null,
            ]);
        }

        $redirect = auth()->user()->isTerminalAdmin()
            ? route('admin.terminals.my')
            : route('admin.terminals.index');

        return redirect($redirect)->with('success', 'Terminal / Adda updated.');
    }

    public function destroy(Terminal $terminal): RedirectResponse
    {
        if ($terminal->busStands()->exists()) {
            return back()->with('error', 'Cannot delete: bus stands are linked to this terminal.');
        }

        if ($terminal->owner) {
            $terminal->owner->update(['is_active' => false]);
        }

        $terminal->delete();

        return redirect()->route('admin.terminals.index')->with('success', 'Terminal removed. Admin account deactivated.');
    }
}
