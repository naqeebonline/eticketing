<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusStand;
use App\Models\Driver;
use App\Services\Driver\DriverService;
use App\Traits\BelongsToBusStand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    use BelongsToBusStand;

    public function __construct(private DriverService $driverService) {}

    public function index(): View
    {
        $query = Driver::query()
            ->with(['busStand.terminal', 'vehicles:id,name,bus_number,driver_id'])
            ->withCount('schedules')
            ->latest();

        $drivers = $this->scopeForBusStandAdmin($query, 'bus_stand_id')->paginate(15);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create(): View
    {
        return view('admin.drivers.create', [
            'busStands' => $this->busStandsForForm(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bus_stand_id' => 'required|exists:bus_stands,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'license_expiry' => 'required|date|after_or_equal:today',
            'license_class' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        abort_unless(auth()->user()->ownsBusStand($validated['bus_stand_id']), 403);

        $this->driverService->create($validated);

        return redirect()
            ->route('admin.drivers.index')
            ->with('success', 'Driver added successfully.');
    }

    public function edit(Driver $driver): View
    {
        $this->authorizeDriver($driver);

        $driver->load(['busStand.terminal', 'vehicles']);

        return view('admin.drivers.edit', [
            'driver' => $driver,
            'busStands' => $this->busStandsForForm(),
        ]);
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $this->authorizeDriver($driver);

        $validated = $request->validate([
            'bus_stand_id' => 'required|exists:bus_stands,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:20',
            'license_number' => 'required|string|max:50',
            'license_expiry' => 'required|date',
            'license_class' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        abort_unless(auth()->user()->ownsBusStand($validated['bus_stand_id']), 403);

        $validated['is_active'] = $request->boolean('is_active');

        $this->driverService->update($driver, $validated);

        return redirect()
            ->route('admin.drivers.index')
            ->with('success', 'Driver updated.');
    }

    private function busStandsForForm()
    {
        $query = BusStand::with('terminal')->where('is_active', true)->orderBy('name');

        if (auth()->user()->isBusStandAdmin()) {
            $ids = auth()->user()->manageableBusStandIds() ?? [];
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }

    private function authorizeDriver(Driver $driver): void
    {
        abort_unless(
            $driver->bus_stand_id && auth()->user()->ownsBusStand($driver->bus_stand_id),
            403
        );
    }
}
