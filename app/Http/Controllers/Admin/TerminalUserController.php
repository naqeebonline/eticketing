<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusStand;
use App\Models\User;
use App\Services\Terminal\TerminalUserService;
use App\Traits\BelongsToTerminal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TerminalUserController extends Controller
{
    use BelongsToTerminal;

    public function __construct(private TerminalUserService $terminalUserService) {}

    public function index(): View
    {
        $terminal = $this->requireTerminal();

        $users = $this->terminalUserService->busStandAdminsFor($terminal);
        $users->load(['assignedBusStands:id,name,address,city,terminal_id']);

        return view('admin.terminal-users.index', compact('terminal', 'users'));
    }

    public function create(): View
    {
        $terminal = $this->requireTerminal();
        $terminalStands = $this->terminalStands($terminal);

        return view('admin.terminal-users.create', compact('terminal', 'terminalStands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $terminal = $this->requireTerminal();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'stand_ids' => 'nullable|array',
            'stand_ids.*' => 'integer|exists:bus_stands,id',
        ]);

        $result = $this->terminalUserService->createForTerminal(
            $terminal,
            $validated,
            $validated['stand_ids'] ?? []
        );

        return redirect()
            ->route('admin.terminal-users.index')
            ->with('success', "User created: {$result['user']->email}. Assign stands anytime from edit.");
    }

    public function edit(User $terminalUser): View
    {
        $terminal = $this->requireTerminal();
        $this->authorizeTerminalUser($terminalUser, $terminal);

        $terminalUser->load('assignedBusStands');
        $terminalStands = $this->terminalStands($terminal);

        return view('admin.terminal-users.edit', [
            'terminal' => $terminal,
            'terminalUser' => $terminalUser,
            'terminalStands' => $terminalStands,
            'assignedIds' => $terminalUser->assignedBusStands->pluck('id')->all(),
        ]);
    }

    public function update(Request $request, User $terminalUser): RedirectResponse
    {
        $terminal = $this->requireTerminal();
        $this->authorizeTerminalUser($terminalUser, $terminal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$terminalUser->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'stand_ids' => 'nullable|array',
            'stand_ids.*' => 'integer|exists:bus_stands,id',
        ]);

        $this->terminalUserService->updateUser(
            $terminalUser,
            $validated,
            $validated['stand_ids'] ?? [],
            $terminal->id
        );

        return redirect()
            ->route('admin.terminal-users.index')
            ->with('success', 'User and stand assignments updated.');
    }

    private function requireTerminal()
    {
        $user = auth()->user();

        if ($user->isTerminalAdmin()) {
            $terminal = $user->primaryTerminal();
            abort_unless($terminal, 404, 'No terminal linked to your account.');

            return $terminal;
        }

        abort(403);
    }

    private function authorizeTerminalUser(User $terminalUser, $terminal): void
    {
        abort_unless(
            $terminalUser->isBusStandAdmin()
            && $terminalUser->terminal_id === $terminal->id,
            404
        );
    }

    private function terminalStands($terminal)
    {
        return BusStand::query()
            ->where('terminal_id', $terminal->id)
            ->with(['assignedUsers:id,name'])
            ->orderBy('name')
            ->get();
    }
}
