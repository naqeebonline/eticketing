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
use Illuminate\Validation\Rule;
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
        return view('admin.vehicles.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateVehicleRequest($request);

        abort_unless(auth()->user()->ownsBusStand($validated['bus_stand_id']), 403);

        $this->registrationService->register(
            $this->vehiclePayload($validated, $request),
            $this->driverPayload($validated),
            $this->ownerPayload($validated),
            $validated['conductors'],
            $validated['seat_rows'],
            (float) $validated['normal_seat_fare'],
            (float) $validated['luxury_seat_fare'],
        );

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Vehicle registered with seat map, driver, and owner.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $this->authorizeVehicle($vehicle);

        $vehicle->load(['driver', 'conductors', 'seatMaps.seats', 'busStand']);

        $seatData = $this->registrationService->formSeatData($vehicle);

        return view('admin.vehicles.edit', array_merge($this->formOptions(), [
            'vehicle' => $vehicle,
            'seatData' => $seatData,
        ]));
    }

    public function show(Vehicle $vehicle): RedirectResponse
    {
        $this->authorizeVehicle($vehicle);

        return redirect()->route('admin.vehicles.edit', $vehicle);
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorizeVehicle($vehicle);

        $validated = $this->validateVehicleRequest($request, $vehicle);

        abort_unless(auth()->user()->ownsBusStand($validated['bus_stand_id']), 403);

        $payload = $this->vehiclePayload($validated, $request);
        $payload['is_active'] = $request->boolean('is_active', $vehicle->is_active);

        $this->registrationService->update(
            $vehicle,
            $payload,
            $this->driverPayload($validated),
            $this->ownerPayload($validated),
            $validated['conductors'],
            $validated['seat_rows'],
            (float) $validated['normal_seat_fare'],
            (float) $validated['luxury_seat_fare'],
        );

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Vehicle details updated.');
    }

    private function formOptions(): array
    {
        $standsQuery = BusStand::with('terminal')->where('is_active', true);
        if (auth()->user()->isBusStandAdmin()) {
            $standsQuery->whereIn('id', auth()->user()->manageableBusStandIds() ?? []);
        }

        return [
            'busStands' => $standsQuery->orderBy('name')->get(),
            'categories' => VehicleCategory::all(),
        ];
    }

    private function validateVehicleRequest(Request $request, ?Vehicle $vehicle = null): array
    {
        $conductors = collect($request->input('conductors', []))
            ->map(function ($conductor) {
                if (($conductor['id'] ?? '') === '') {
                    $conductor['id'] = null;
                }

                $conductor['name'] = trim((string) ($conductor['name'] ?? ''));

                return $conductor;
            })
            // Empty rows are ignored — conductors are optional
            ->filter(fn ($conductor) => $conductor['name'] !== '')
            ->values()
            ->all();

        $request->merge(['conductors' => $conductors]);

        $validated = $request->validate([
            'bus_stand_id' => 'required|exists:bus_stands,id',
            'vehicle_category_id' => 'nullable|exists:vehicle_categories,id',
            'name' => 'required|string|max:255',
            'bus_number' => 'required|string|max:50',
            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vehicles', 'registration_number')->ignore($vehicle?->id),
            ],
            'total_seats' => 'required|integer|min:1|max:80',
            'bus_type' => 'required|string|in:standard,luxury,sleeper',
            'is_ac' => 'boolean',
            'is_active' => 'boolean',
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
            'conductors' => 'nullable|array',
            'conductors.*.id' => 'nullable|integer',
            'conductors.*.name' => 'required|string|max:255',
            'conductors.*.phone' => 'nullable|string|max:20',
            'conductors.*.cnic' => 'nullable|string|max:20',
        ]);

        $validated['conductors'] = $validated['conductors'] ?? [];

        foreach ($validated['seat_rows'] as $i => $row) {
            if ((int) $row['left'] + (int) $row['right'] < 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "seat_rows.$i" => 'Each row needs at least one seat.',
                ]);
            }
        }

        return $validated;
    }

    /** @param  array<string, mixed>  $validated */
    private function vehiclePayload(array $validated, Request $request): array
    {
        return [
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
        ];
    }

    /** @param  array<string, mixed>  $validated */
    private function driverPayload(array $validated): array
    {
        return [
            'name' => $validated['driver_name'],
            'phone' => $validated['driver_phone'] ?? null,
            'cnic' => $validated['driver_cnic'] ?? null,
            'license_number' => $validated['driver_license_number'] ?? null,
        ];
    }

    /** @param  array<string, mixed>  $validated */
    private function ownerPayload(array $validated): array
    {
        return [
            'name' => $validated['owner_name'],
            'phone' => $validated['owner_phone'] ?? null,
        ];
    }

    private function authorizeVehicle(Vehicle $vehicle): void
    {
        abort_unless(
            auth()->user()->ownsBusStand($vehicle->bus_stand_id),
            403
        );
    }
}
