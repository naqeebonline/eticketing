@extends('layouts.app')
@section('title', 'Passenger Details')

@section('content')
@php
    $seatCount = count($seatIds);
    $defaultMale = old('male_count', $seatCount === 1 ? 1 : 0);
    $defaultFemale = old('female_count', 0);
    $defaultChild = old('child_count', 0);
@endphp
<div class="booking-flow-shell">
    <x-public.booking-steps :current="3" />

    <div class="booking-flow-grid">
        <div>
            <div class="booking-page-header">
                <h1 class="booking-page-title">Passenger details</h1>
                <p class="mt-2 text-slate-500">
                    Ek CNIC par {{ $seatCount }} seat{{ $seatCount === 1 ? '' : 's' }} — male, female aur child batayein
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('book.passengers.store', $schedule->uuid) }}"
                class="space-y-4"
                x-data="{
                    male: {{ (int) $defaultMale }},
                    female: {{ (int) $defaultFemale }},
                    child: {{ (int) $defaultChild }},
                    seatCount: {{ $seatCount }},
                    get total() { return this.male + this.female + this.child },
                    get isValid() { return this.total === this.seatCount && this.seatCount > 0 }
                }"
                x-on:submit="if (!isValid) { $event.preventDefault(); $store.dialog.alert({ title: 'Traveler count', message: 'Male, female aur child ka total ' + seatCount + ' seats ke barabar hona chahiye.', variant: 'warning' }); }"
            >
                @csrf

                @error('travelers')
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300">
                    {{ $message }}
                </div>
                @enderror

                <div class="search-card p-6">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 text-sm font-bold text-white shadow-md">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <div>
                            <p class="font-display font-semibold text-slate-900 dark:text-white">Contact (sab seats ke liye)</p>
                            <p class="text-sm text-slate-500">Yeh CNIC poori booking par lagega</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="form-label">Full name</label>
                            <input type="text" name="full_name" required class="input-field" placeholder="As on CNIC" value="{{ old('full_name') }}">
                        </div>
                        <div>
                            <label class="form-label">CNIC</label>
                            <input type="text" name="cnic" required class="input-field" placeholder="35202-1234567-1" value="{{ old('cnic') }}">
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="input-field" placeholder="03XX XXXXXXX" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="search-card p-6">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <div>
                                <p class="font-display font-semibold text-slate-900 dark:text-white">Travelers</p>
                                <p class="text-sm text-slate-500">Total {{ $seatCount }} seat{{ $seatCount === 1 ? '' : 's' }} ke mutabiq</p>
                            </div>
                        </div>
                        <span
                            class="rounded-lg px-3 py-1 text-sm font-semibold"
                            :class="isValid ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'"
                            x-text="total + ' / ' + seatCount + ' seats'"
                        ></span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="form-label">Male</label>
                            <input type="number" name="male_count" min="0" :max="seatCount" required class="input-field" x-model.number="male">
                        </div>
                        <div>
                            <label class="form-label">Female</label>
                            <input type="number" name="female_count" min="0" :max="seatCount" required class="input-field" x-model.number="female">
                        </div>
                        <div>
                            <label class="form-label">Child</label>
                            <input type="number" name="child_count" min="0" :max="seatCount" required class="input-field" x-model.number="child">
                        </div>
                    </div>

                    <p class="mt-3 text-sm text-slate-500" x-show="!isValid">
                        Male + Female + Child = <span class="font-semibold" x-text="seatCount"></span> hona chahiye.
                    </p>
                </div>

                <div class="search-card p-6">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Selected seats</p>
                    <div class="flex flex-wrap gap-2">
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

                <x-ui.button type="submit" class="w-full btn-lg shadow-lg shadow-primary-500/25" x-bind:disabled="!isValid">
                    Confirm booking
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </x-ui.button>
            </form>
        </div>

        <div class="hidden lg:block">
            <x-public.trip-summary
                :from="$schedule->route->departure_city"
                :to="$schedule->route->destination_city"
                :date="$schedule->departure_date->format('l, M d, Y')"
                :time="\Carbon\Carbon::parse($schedule->departure_time)->format('h:i A')"
                :bus="($schedule->vehicle->name ?? 'Bus').' · '.($schedule->vehicle->bus_number ?? '')"
                :seats="collect($seatIds)->map(fn ($id) => $seats->get($id)?->seat_number)->filter()->join(', ')"
                :amount="'PKR '.number_format($totalFare)"
            />
        </div>
    </div>
</div>
@endsection
