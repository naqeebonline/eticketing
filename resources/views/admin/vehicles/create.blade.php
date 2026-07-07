@extends('layouts.admin')
@section('title', 'Add Vehicle')
@section('header', 'Add Vehicle')
@section('breadcrumb', 'Register bus & seat map')

@section('content')
<div class="admin-form-shell max-w-4xl" x-data="vehicleStaffForm()">
    <x-ui.page-header title="New vehicle" subtitle="Bus details, staff assignments, and seat map" />

    <form method="POST" action="{{ route('admin.vehicles.store') }}" class="space-y-6">
        @csrf

        <div class="card space-y-5">
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Bus stand & vehicle</h2>
            <x-ui.select label="Bus stand" name="bus_stand_id" required>
                @foreach($busStands as $stand)
                <option value="{{ $stand->id }}" @selected(old('bus_stand_id') == $stand->id)>
                    {{ $stand->name }}
                    @if($stand->terminal) — {{ $stand->terminal->name }} @endif
                    ({{ $stand->city }})
                </option>
                @endforeach
            </x-ui.select>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.select label="Category" name="vehicle_category_id">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('vehicle_category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.select label="Bus type" name="bus_type" required>
                    <option value="standard" @selected(old('bus_type') === 'standard')>Standard</option>
                    <option value="luxury" @selected(old('bus_type') === 'luxury')>Luxury</option>
                    <option value="sleeper" @selected(old('bus_type') === 'sleeper')>Sleeper</option>
                </x-ui.select>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Bus name" name="name" required placeholder="Metro Express" :value="old('name')" />
                <x-ui.input label="Bus number" name="bus_number" required :value="old('bus_number')" />
                <x-ui.input label="Registration number" name="registration_number" required class="sm:col-span-2" :value="old('registration_number')" />
                <div>
                    <label class="form-label">Total seats</label>
                    <input type="number" name="total_seats" :value="totalSeats()" readonly class="input-field bg-slate-50 dark:bg-slate-900/50">
                    <p class="form-hint">Auto-calculated from seat map</p>
                </div>
                <x-ui.input label="Luxury type" name="luxury_type" hint="Optional" :value="old('luxury_type')" />
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_ac" value="1" @checked(old('is_ac', true)) class="rounded border-slate-300 text-primary-600">
                <span class="text-sm font-medium">Air conditioned (AC)</span>
            </label>
        </div>

        <div class="card space-y-5 border-primary-200/60 dark:border-primary-800/60">
            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Vehicle owner</h2>
                <p class="mt-1 text-sm text-slate-500">Company or individual who owns this bus (not the driver).</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Owner name" name="owner_name" required placeholder="Full name or company" :value="old('owner_name')" />
                <x-ui.input label="Owner phone" name="owner_phone" type="tel" placeholder="03XX XXXXXXX" :value="old('owner_phone')" />
            </div>
        </div>

        <div class="card space-y-5">
            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Driver</h2>
                <p class="mt-1 text-sm text-slate-500">Assigned driver for this vehicle.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Driver name" name="driver_name" required :value="old('driver_name')" />
                <x-ui.input label="Driver phone" name="driver_phone" type="tel" :value="old('driver_phone')" />
                <x-ui.input label="Driver CNIC" name="driver_cnic" placeholder="Optional" :value="old('driver_cnic')" />
                <x-ui.input label="License number" name="driver_license_number" hint="Optional — auto-generated if empty" :value="old('driver_license_number')" />
            </div>
        </div>

        <div class="card space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Conductors</h2>
                    <p class="mt-1 text-sm text-slate-500">Add one or more conductors. The first is marked primary.</p>
                </div>
                <button type="button" @click="addConductor()" class="btn-secondary btn-sm shrink-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add conductor
                </button>
            </div>

            <template x-for="(conductor, index) in conductors" :key="index">
                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-900/30">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Conductor <span x-text="index + 1"></span>
                            <span x-show="index === 0" class="ml-2 text-xs font-medium text-primary-600">(Primary)</span>
                        </span>
                        <button type="button" x-show="conductors.length > 1" @click="removeConductor(index)" class="text-sm text-danger-600 hover:text-danger-700">Remove</button>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="form-label">Name</label>
                            <input type="text" :name="'conductors[' + index + '][name]'" x-model="conductor.name" required class="input-field" placeholder="Full name">
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" :name="'conductors[' + index + '][phone]'" x-model="conductor.phone" class="input-field" placeholder="Optional">
                        </div>
                        <div>
                            <label class="form-label">CNIC</label>
                            <input type="text" :name="'conductors[' + index + '][cnic]'" x-model="conductor.cnic" class="input-field" placeholder="Optional">
                        </div>
                    </div>
                </div>
            </template>
            @error('conductors')<p class="form-error">{{ $message }}</p>@enderror
            @error('conductors.*')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="card space-y-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Seat map</h2>
                    <p class="mt-1 text-sm text-slate-500">Har row ke left aur right side ka alag type (Normal/Luxury) aur fare set karein.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="applyTemplate('front11_rest22')" class="btn-secondary btn-sm">1+1 + 2+2</button>
                    <button type="button" @click="applyTemplate('all22')" class="btn-secondary btn-sm">All 2+2</button>
                    <button type="button" @click="addSeatRow()" class="btn-primary btn-sm">+ Row</button>
                </div>
            </div>
            <div class="seat-fare-bar">
                <div class="seat-fare-bar__item seat-fare-bar__item--normal">
                    <label for="normal_seat_fare" class="seat-fare-bar__label">Normal seat fare</label>
                    <div class="seat-fare-bar__input">
                        <span class="seat-fare-bar__prefix">PKR</span>
                        <input id="normal_seat_fare" type="number" name="normal_seat_fare" step="0.01" min="0" required class="input-field" x-model.number="normalFare" value="{{ old('normal_seat_fare', 2000) }}">
                    </div>
                    @error('normal_seat_fare')<p class="form-error mt-2">{{ $message }}</p>@enderror
                </div>
                <div class="seat-fare-bar__item seat-fare-bar__item--luxury">
                    <label for="luxury_seat_fare" class="seat-fare-bar__label">Luxury seat fare</label>
                    <div class="seat-fare-bar__input">
                        <span class="seat-fare-bar__prefix">PKR</span>
                        <input id="luxury_seat_fare" type="number" name="luxury_seat_fare" step="0.01" min="0" required class="input-field" x-model.number="luxuryFare" value="{{ old('luxury_seat_fare', 3500) }}">
                    </div>
                    @error('luxury_seat_fare')<p class="form-error mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="seat-row-list">
            <template x-for="(row, index) in seatRows" :key="index">
                <div class="seat-row-config">
                    <div class="seat-row-config__header">
                        <span class="seat-row-config__badge" x-text="'Row ' + (index + 1)"></span>
                        <button type="button" x-show="seatRows.length > 1" @click="removeSeatRow(index)" class="text-xs font-semibold text-danger-600 hover:text-danger-700">Remove row</button>
                    </div>
                    <div class="seat-row-config__body">
                        <div class="seat-row-config__zone seat-row-config__zone--left">
                            <span class="seat-row-config__zone-label">Left side</span>
                            <div class="seat-row-config__fields">
                                <div>
                                    <label class="form-label">Seats</label>
                                    <select class="input-field" x-model.number="row.left" :name="'seat_rows[' + index + '][left]'">
                                        <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Type</label>
                                    <input type="hidden" :name="'seat_rows[' + index + '][left_type]'" :value="row.left_type">
                                    <div class="seat-type-pills">
                                        <button type="button" class="seat-type-pills__btn"
                                            :class="row.left_type === 'normal' && 'seat-type-pills__btn--active-normal'"
                                            @click="row.left_type = 'normal'">Normal</button>
                                        <button type="button" class="seat-type-pills__btn"
                                            :class="row.left_type === 'luxury' && 'seat-type-pills__btn--active-luxury'"
                                            @click="row.left_type = 'luxury'">Luxury</button>
                                    </div>
                                </div>
                            </div>
                            <div class="seat-row-config__fare" :class="row.left_type === 'luxury' && 'seat-row-config__fare--luxury'">
                                <span class="text-xs font-medium opacity-70">Fare</span>
                                PKR <span x-text="sideFare(row.left_type).toLocaleString('en-PK')"></span>
                            </div>
                        </div>

                        <div class="seat-row-config__aisle" aria-hidden="true"><span>Aisle</span></div>

                        <div class="seat-row-config__zone seat-row-config__zone--right">
                            <span class="seat-row-config__zone-label">Right side</span>
                            <div class="seat-row-config__fields">
                                <div>
                                    <label class="form-label">Seats</label>
                                    <select class="input-field" x-model.number="row.right" :name="'seat_rows[' + index + '][right]'">
                                        <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Type</label>
                                    <input type="hidden" :name="'seat_rows[' + index + '][right_type]'" :value="row.right_type">
                                    <div class="seat-type-pills">
                                        <button type="button" class="seat-type-pills__btn"
                                            :class="row.right_type === 'normal' && 'seat-type-pills__btn--active-normal'"
                                            @click="row.right_type = 'normal'">Normal</button>
                                        <button type="button" class="seat-type-pills__btn"
                                            :class="row.right_type === 'luxury' && 'seat-type-pills__btn--active-luxury'"
                                            @click="row.right_type = 'luxury'">Luxury</button>
                                    </div>
                                </div>
                            </div>
                            <div class="seat-row-config__fare" :class="row.right_type === 'luxury' && 'seat-row-config__fare--luxury'">
                                <span class="text-xs font-medium opacity-70">Fare</span>
                                PKR <span x-text="sideFare(row.right_type).toLocaleString('en-PK')"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            </div>
            @error('seat_rows')<p class="form-error">{{ $message }}</p>@enderror

            <div class="seat-map-preview">
                <div class="seat-map-preview__header">
                    <div>
                        <p class="seat-map-preview__title">Live seat map preview</p>
                        <p class="seat-map-preview__meta">
                            <span x-text="totalSeats() + ' seats'"></span>
                            <span> · </span>
                            <span x-text="normalSeatCount() + ' normal'"></span>
                            <span> · </span>
                            <span x-text="luxurySeatCount() + ' luxury'"></span>
                        </p>
                    </div>
                    <div class="seat-map-preview__legend">
                        <span class="seat-map-preview__legend-item">
                            <span class="seat-map-preview__legend-swatch"></span>
                            Normal
                        </span>
                        <span class="seat-map-preview__legend-item">
                            <span class="seat-map-preview__legend-swatch seat-map-preview__legend-swatch--luxury"></span>
                            Luxury
                        </span>
                    </div>
                </div>

                <div class="seat-map-preview__bus">
                    <div class="seat-map-preview__front">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        Front · Driver
                    </div>

                    <div class="seat-map-preview__rows">
                        <template x-for="(row, index) in seatRows" :key="'p'+index">
                            <div class="seat-map-preview__row" x-show="row.left + row.right > 0">
                                <span class="seat-map-preview__row-num" x-text="index + 1"></span>
                                <div class="seat-map-preview__layout"
                                    :class="{
                                        'bus-seat-row--right-only': row.left === 0 && row.right > 0,
                                        'bus-seat-row--left-only': row.right === 0 && row.left > 0,
                                    }">
                                    <div class="seat-map-preview__zone seat-map-preview__zone--left">
                                        <div class="seat-map-preview__zone-head"
                                            x-show="row.left > 0"
                                            :class="row.left_type === 'luxury' && 'seat-map-preview__zone-head--luxury'">
                                            <span class="seat-map-preview__zone-tag" x-text="'Left · ' + (row.left_type === 'luxury' ? 'Luxury' : 'Normal')"></span>
                                            <span class="seat-map-preview__zone-fare" x-text="'PKR ' + sideFare(row.left_type).toLocaleString('en-PK')"></span>
                                        </div>
                                        <div class="seat-map-preview__seats">
                                            <template x-for="n in row.left" :key="'l'+n">
                                                <span class="bus-seat bus-seat-preview bus-seat-preview--labeled"
                                                    :class="row.left_type === 'luxury' ? 'bus-seat--luxury' : ''"
                                                    :title="'Seat ' + seatLabel(index, n, 'L') + ' · PKR ' + sideFare(row.left_type).toLocaleString('en-PK')">
                                                    <span x-text="seatLabel(index, n, 'L')"></span>
                                                    <small x-text="row.left_type === 'luxury' ? 'Lux' : 'Std'"></small>
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="seat-map-preview__aisle"
                                        :class="row.left === 0 && row.right > 0 ? 'seat-map-preview__aisle--spacer' : ''"
                                        x-show="row.left > 0 || row.right > 0"
                                        aria-hidden="true"></div>

                                    <div class="seat-map-preview__zone seat-map-preview__zone--right">
                                        <div class="seat-map-preview__zone-head"
                                            x-show="row.right > 0"
                                            :class="row.right_type === 'luxury' && 'seat-map-preview__zone-head--luxury'">
                                            <span class="seat-map-preview__zone-tag" x-text="'Right · ' + (row.right_type === 'luxury' ? 'Luxury' : 'Normal')"></span>
                                            <span class="seat-map-preview__zone-fare" x-text="'PKR ' + sideFare(row.right_type).toLocaleString('en-PK')"></span>
                                        </div>
                                        <div class="seat-map-preview__seats">
                                            <template x-for="n in row.right" :key="'r'+n">
                                                <span class="bus-seat bus-seat-preview bus-seat-preview--labeled"
                                                    :class="row.right_type === 'luxury' ? 'bus-seat--luxury' : ''"
                                                    :title="'Seat ' + seatLabel(index, n, 'R') + ' · PKR ' + sideFare(row.right_type).toLocaleString('en-PK')">
                                                    <span x-text="seatLabel(index, n, 'R')"></span>
                                                    <small x-text="row.right_type === 'luxury' ? 'Lux' : 'Std'"></small>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <x-ui.button type="submit">Create vehicle</x-ui.button>
            <x-ui.button href="{{ route('admin.vehicles.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@php
    $initialConductors = old('conductors', [['name' => '', 'phone' => '', 'cnic' => '']]);
    $defaultSeatRows = array_map(fn () => ['left' => 2, 'right' => 2, 'left_type' => 'normal', 'right_type' => 'normal'], range(1, 10));
    $defaultSeatRows[0] = ['left' => 1, 'right' => 1, 'left_type' => 'normal', 'right_type' => 'normal'];
    $initialSeatRows = old('seat_rows', $defaultSeatRows);
@endphp
<script>
function vehicleStaffForm() {
    const oldConductors = @json($initialConductors);
    const oldSeatRows = @json($initialSeatRows);

    const normalizeType = (value) => value === 'luxury' ? 'luxury' : 'normal';
    const normalizeRow = (r) => {
        const legacy = normalizeType(r.row_type);
        return {
            left: Number(r.left ?? 2),
            right: Number(r.right ?? 2),
            left_type: normalizeType(r.left_type ?? legacy),
            right_type: normalizeType(r.right_type ?? legacy),
        };
    };

    return {
        conductors: oldConductors.length ? oldConductors : [{ name: '', phone: '', cnic: '' }],
        seatRows: oldSeatRows.length ? oldSeatRows.map(normalizeRow) : [{ left: 1, right: 1, left_type: 'normal', right_type: 'normal' }, ...Array.from({ length: 9 }, () => ({ left: 2, right: 2, left_type: 'normal', right_type: 'normal' }))],
        normalFare: Number(@json(old('normal_seat_fare', 2000))),
        luxuryFare: Number(@json(old('luxury_seat_fare', 3500))),
        addConductor() { this.conductors.push({ name: '', phone: '', cnic: '' }); },
        removeConductor(index) { if (this.conductors.length > 1) this.conductors.splice(index, 1); },
        addSeatRow() { this.seatRows.push({ left: 2, right: 2, left_type: 'normal', right_type: 'normal' }); },
        removeSeatRow(index) { if (this.seatRows.length > 1) this.seatRows.splice(index, 1); },
        totalSeats() { return this.seatRows.reduce((s, r) => s + Number(r.left) + Number(r.right), 0); },
        normalSeatCount() {
            return this.seatRows.reduce((s, r) => {
                let n = 0;
                if (Number(r.left) > 0 && r.left_type !== 'luxury') n += Number(r.left);
                if (Number(r.right) > 0 && r.right_type !== 'luxury') n += Number(r.right);
                return s + n;
            }, 0);
        },
        luxurySeatCount() {
            return this.seatRows.reduce((s, r) => {
                let n = 0;
                if (Number(r.left) > 0 && r.left_type === 'luxury') n += Number(r.left);
                if (Number(r.right) > 0 && r.right_type === 'luxury') n += Number(r.right);
                return s + n;
            }, 0);
        },
        sideFare(type) { return type === 'luxury' ? Number(this.luxuryFare) || 0 : Number(this.normalFare) || 0; },
        seatLabel(rowIndex, n, side) {
            let before = 0;
            for (let i = 0; i < rowIndex; i++) before += Number(this.seatRows[i].left) + Number(this.seatRows[i].right);
            if (side === 'L') return String(before + n);
            return String(before + Number(this.seatRows[rowIndex].left) + n);
        },
        applyTemplate(t) {
            if (t === 'front11_rest22') {
                this.seatRows = [{ left: 1, right: 1, left_type: 'normal', right_type: 'normal' }];
                for (let i = 0; i < 9; i++) this.seatRows.push({ left: 2, right: 2, left_type: 'normal', right_type: 'normal' });
            } else if (t === 'all22') {
                this.seatRows = Array.from({ length: 10 }, () => ({ left: 2, right: 2, left_type: 'normal', right_type: 'normal' }));
            }
        },
    };
}
</script>
@endpush
