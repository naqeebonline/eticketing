@extends('layouts.app')
@section('title', $from.' to '.$to.' — Bus Tickets')

@section('content')
<div class="booking-flow-shell">
    <x-public.booking-steps :current="1" />

    <div class="booking-page-header">
        <h1 class="booking-page-title">
            {{ $from }} <span class="text-primary-500">→</span> {{ $to }}
        </h1>
        <div class="booking-route-chip">
            <span class="booking-route-meta">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</span>
            <span class="text-slate-300">·</span>
            <span class="font-semibold text-primary-600">{{ $schedules->count() }} {{ Str::plural('bus', $schedules->count()) }} found</span>
        </div>
    </div>

    <div class="mb-8">
        <x-public.search-form :from="$from" :to="$to" :date="$date" compact />
    </div>

    <div class="space-y-4">
        @forelse($schedules as $schedule)
        <article class="schedule-card-premium">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex gap-4">
                    <div class="hidden h-16 w-16 shrink-0 flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 text-white shadow-lg shadow-primary-500/20 sm:flex">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16c0 .88.39 1.67 1 2.22V20a1 1 0 001 1h1a1 1 0 001-1v-1h8v1a1 1 0 001 1h1a1 1 0 001-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-2.21-1.79-4-4-4H8C5.79 2 4 3.79 4 6v10z"/></svg>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-slate-900 dark:text-white">{{ $schedule->vehicle->name ?? 'Bus' }}</p>
                        <p class="text-sm text-slate-500">{{ $schedule->vehicle->bus_number ?? '' }} · {{ $schedule->route->busStand->name ?? 'Operator' }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-sm font-semibold text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                                <svg class="h-4 w-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                            </span>
                            <x-ui.badge :variant="$schedule->vehicle->is_ac ? 'primary' : 'neutral'">{{ $schedule->vehicle->is_ac ? 'AC' : 'Non-AC' }}</x-ui.badge>
                            <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ $schedule->available_seats }} seats left</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-4 lg:border-0 lg:pt-0 dark:border-slate-700">
                    <div class="text-left lg:text-right">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Per seat</p>
                        <p class="font-display text-2xl font-bold text-primary-600 dark:text-primary-400">PKR {{ number_format($schedule->fare) }}</p>
                    </div>
                    <x-ui.button href="{{ route('book.seats', $schedule->uuid) }}" class="shrink-0 shadow-md shadow-primary-500/20">
                        Select seats
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </x-ui.button>
                </div>
            </div>
        </article>
        @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center dark:border-slate-700 dark:bg-slate-900/50">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <h3 class="font-display mt-4 text-lg font-bold text-slate-900 dark:text-white">No buses on this route</h3>
            <p class="mt-2 text-sm text-slate-500">Try another date or change your cities.</p>
            <x-ui.button href="{{ route('home') }}" variant="outline" class="mt-6">New search</x-ui.button>
        </div>
        @endforelse
    </div>
</div>
@endsection
