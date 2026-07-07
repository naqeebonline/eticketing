@extends('layouts.admin')
@section('title', 'Passenger Details')
@section('header', 'New Booking')
@section('breadcrumb', 'Passenger details')

@section('content')
@php
    $seatCount = count($seatIds);
    $defaultMale = old('male_count', $seatCount === 1 ? 1 : 0);
    $defaultFemale = old('female_count', 0);
    $defaultChild = old('child_count', 0);
@endphp

<a href="{{ route('admin.bookings.seats', $schedule) }}" class="admin-row-action mb-6 inline-flex">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to seats
</a>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-ui.page-header
            title="Passenger details"
            :subtitle="'Ek CNIC par '.$seatCount.' seat'.($seatCount === 1 ? '' : 's').' — male, female aur child batayein'"
        />

        <form
            method="POST"
            action="{{ route('admin.bookings.passengers.store', $schedule) }}"
            class="space-y-4"
            x-data="{
                male: {{ (int) $defaultMale }},
                female: {{ (int) $defaultFemale }},
                child: {{ (int) $defaultChild }},
                seatCount: {{ $seatCount }},
                bookingAction: @js(old('booking_action', 'confirm')),
                get total() { return this.male + this.female + this.child },
                get isValid() { return this.total === this.seatCount && this.seatCount > 0 }
            }"
            x-on:submit="if (!isValid) { $event.preventDefault(); $store.dialog.alert({ title: 'Traveler count', message: 'Male, female aur child ka total ' + seatCount + ' seats ke barabar hona chahiye.', variant: 'warning' }); }"
        >
            @csrf
            <input type="hidden" name="payment_method" value="cash">

            @error('travelers')
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300">
                {{ $message }}
            </div>
            @enderror

            <div class="admin-panel p-6">
                <h3 class="admin-panel-title">Contact (sab seats ke liye)</h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-ui.input label="Full name" name="full_name" :value="old('full_name')" required placeholder="As on CNIC" />
                    </div>
                    <x-ui.input label="CNIC" name="cnic" :value="old('cnic')" required placeholder="35202-1234567-1" />
                    <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone')" placeholder="03XX XXXXXXX" />
                </div>
            </div>

            <div class="admin-panel p-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <h3 class="admin-panel-title !mb-0">Travelers</h3>
                    <span
                        class="rounded-lg px-3 py-1 text-sm font-semibold"
                        :class="isValid ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'"
                        x-text="total + ' / ' + seatCount + ' seats'"
                    ></span>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="form-label" for="male_count">Male</label>
                        <input type="number" id="male_count" name="male_count" min="0" :max="seatCount" required class="input-field" x-model.number="male">
                    </div>
                    <div>
                        <label class="form-label" for="female_count">Female</label>
                        <input type="number" id="female_count" name="female_count" min="0" :max="seatCount" required class="input-field" x-model.number="female">
                    </div>
                    <div>
                        <label class="form-label" for="child_count">Child</label>
                        <input type="number" id="child_count" name="child_count" min="0" :max="seatCount" required class="input-field" x-model.number="child">
                    </div>
                </div>
                <p class="mt-3 text-sm text-slate-500" x-show="!isValid">
                    Male + Female + Child = <span class="font-semibold" x-text="seatCount"></span> hona chahiye.
                </p>
            </div>

            <div class="admin-panel p-6">
                <h3 class="admin-panel-title">Selected seats</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($seatIds as $seatId)
                    @php $seat = $seats->get($seatId); @endphp
                    @if($seat)
                    <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <span class="text-primary-600">{{ $seat->seat_number }}</span>
                        <span class="text-slate-400">·</span>
                        PKR {{ number_format($seat->fareForSchedule($schedule)) }}
                    </span>
                    @endif
                    @endforeach
                </div>
            </div>

            <div class="admin-panel p-6">
                <h3 class="admin-panel-title">Booking status</h3>
                <p class="mt-1 text-sm text-slate-500">Confirm = payment abhi receive. Held = seats reserve, payment baad mein.</p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border px-4 py-3 transition"
                        :class="bookingAction === 'confirm' ? 'border-primary-400 bg-primary-50/80 dark:border-primary-600 dark:bg-primary-950/40' : 'border-slate-200 dark:border-slate-700'">
                        <input type="radio" name="booking_action" value="confirm" class="mt-1 text-primary-600" x-model="bookingAction">
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 dark:text-white">Confirm & receive payment</span>
                            <span class="mt-0.5 block text-xs text-slate-500">Cash abhi receive — booking confirmed</span>
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border px-4 py-3 transition"
                        :class="bookingAction === 'held' ? 'border-amber-400 bg-amber-50/80 dark:border-amber-600 dark:bg-amber-950/40' : 'border-slate-200 dark:border-slate-700'">
                        <input type="radio" name="booking_action" value="held" class="mt-1 text-amber-600" x-model="bookingAction">
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 dark:text-white">Hold only</span>
                            <span class="mt-0.5 block text-xs text-slate-500">Payment nahi — baad mein confirm karein</span>
                        </span>
                    </label>
                </div>
                @error('booking_action')<p class="mt-2 form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-wrap gap-3">
                <x-ui.button type="submit" x-bind:disabled="!isValid">
                    <span x-show="bookingAction === 'confirm'">Confirm & receive payment</span>
                    <span x-show="bookingAction === 'held'" x-cloak>Save as held</span>
                </x-ui.button>
                <x-ui.button href="{{ route('admin.bookings.seats', $schedule) }}" variant="secondary">Change seats</x-ui.button>
            </div>
        </form>
    </div>

    <div class="admin-panel h-fit p-6">
        <h3 class="admin-panel-title">Trip summary</h3>
        <dl class="mt-5 space-y-4 text-sm">
            <div>
                <dt class="text-slate-500">Route</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $schedule->route->departure_city }} → {{ $schedule->route->destination_city }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Departure</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $schedule->departure_date->format('M d, Y') }}</dd>
                <dd class="text-slate-500">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Bus</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $schedule->vehicle->name ?? '—' }}</dd>
            </div>
            <div class="border-t border-slate-100 pt-4 dark:border-slate-800">
                <dt class="text-slate-500">Total fare</dt>
                <dd class="mt-1 font-display text-2xl font-bold text-primary-600">PKR {{ number_format($totalFare) }}</dd>
                <dd class="mt-2 text-xs text-slate-500">Payment: cash at counter</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
