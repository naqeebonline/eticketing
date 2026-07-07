<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusStand;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\Vehicle\VehicleRegistrationService;
use App\Traits\BelongsToBusStand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    use BelongsToBusStand;

    public function __construct(
        private VehicleRegistrationService $registrationService,
    ) {}

    public function index(): View
    {
        $query = Vehicle::with(['busStand.terminal', 'driver', 'conductors', 'category'])->latest();
        $vehicles = $this->scopeForBusStandAdmin($query)->paginate(15);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        $standsQuery = BusStand::with('terminal')->where('is_active', true);
        if (auth()->user()->isBusStandAdmin()) {
            $standsQuery->whereIn('id', auth()->user()->manageableBusStandIds() ?? []);
        }

        return view('admin.vehicles.create', [
            'busStands' => $standsQuery->get(),
            'categories' => VehicleCategory::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bus_stand_id' => 'required|exists:bus_stands,id',
            'vehicle_category_id' => 'nullable|exists:vehicle_categories,id',
            'name' => 'required|string|max:255',
            'bus_number' => 'required|string|max:50',
            'registration_number' => 'required|string|max:50|unique:vehicles',
            'total_seats' => 'required|integer|min:1|max:80',
            'bus_type' => 'required|string|in:standard,luxury,sleeper',
            'is_ac' => 'boolean',
            'luxury_type' => 'nullable|string|max:100',
            'seat_rows' => 'required|array|min:1|max:25',
            'seat_rows.*.left' => 'required|integer|min:0|max:4',
            'seat_rows.*.right' => 'required|integer|min:0|max:4',
            'seat_rows.*.left_type' => 'required|in:normal,luxury',
            'seat_rows.*.right_type' => 'required|in:normal,luxury',
            'normal_seat_fare' => 'required|numeric|min:0',
            'luxury_seat_fare' => 'required|numeric|min:0',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'nullable|string|max:20',
            'driver_cnic' => 'nullable|string|max:20',
            'driver_license_number' => 'nullable|string|max:50',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'conductors' => 'required|array|min:1',
            'conductors.*.name' => 'required|string|max:255',
            'conductors.*.phone' => 'nullable|string|max:20',
            'conductors.*.cnic' => 'nullable|string|max:20',
        ]);

        foreach ($validated['seat_rows'] as $i => $row) {
            if ((int) $row['left'] + (int) $row['right'] < 1) {
                return back()->withErrors(["seat_rows.$i" => 'Each row needs at least one seat.'])->withInput();
            }
        }

        abort_unless(auth()->user()->ownsBusStand($validated['bus_stand_id']), 403);

        $this->registrationService->register(
            [
                'bus_stand_id' => $validated['bus_stand_id'],
                'vehicle_category_id' => $validated['vehicle_category_id'] ?? null,
                'name' => $validated['name'],
                'bus_number' => $validated['bus_number'],
                'registration_number' => $validated['registration_number'],
                'total_seats' => $validated['total_seats'],
                'bus_type' => $validated['bus_type'],
                'is_ac' => $request->boolean('is_ac'),
                'luxury_type' => $validated['luxury_type'] ?? null,
                'is_active' => true,
            ],
            [
                'name' => $validated['driver_name'],
                'phone' => $validated['driver_phone'] ?? null,
                'cnic' => $validated['driver_cnic'] ?? null,
                'license_number' => $validated['driver_license_number'] ?? null,
            ],
            [
                'name' => $validated['owner_name'],
                'phone' => $validated['owner_phone'] ?? null,
            ],
            $validated['conductors'],
            $validated['seat_rows'],
            (float) $validated['normal_seat_fare'],
            (float) $validated['luxury_seat_fare'],
        );

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Vehicle registered with seat map, driver, owner, and conductor(s).');
    }
}
