@extends('layouts.admin')
@section('title', $isPlatformView ? 'Platform Dashboard' : ($isTerminalView ? 'Terminal Dashboard' : 'Dashboard'))
@section('header', $isPlatformView ? 'Platform Dashboard' : ($isTerminalView ? 'Terminal Dashboard' : 'Dashboard'))
@section('breadcrumb', $isPlatformView ? 'System-wide overview' : ($isTerminalView ? 'Terminal & stands overview' : 'Fleet & bookings overview'))

@section('content')
@if($isPlatformView)
<div class="admin-platform-hero mb-8">
    <div class="admin-platform-hero-glow" aria-hidden="true"></div>
    <p class="admin-platform-tag">Platform control</p>
    <h2 class="admin-hero-title mt-1">Welcome, {{ explode(' ', auth()->user()->name)[0] }}</h2>
    <p class="admin-hero-sub">
        Manage terminals, bus stands, and cities. Stand admins handle fleet, schedules, and counter bookings.
    </p>
</div>

<div class="admin-quick-grid mb-8">
    <x-admin.quick-action href="{{ route('admin.cities.index') }}" label="Cities" hint="Booking destinations" icon="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
    <x-admin.quick-action href="{{ route('admin.terminals.create') }}" label="Add terminal" hint="New terminal / adda" icon="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
    <x-admin.quick-action href="{{ route('admin.bus-stands.create') }}" label="Add bus stand" hint="Under a terminal" icon="M12 4v16m8-8H4" />
    <x-admin.quick-action href="{{ route('admin.terminals.index') }}" label="All terminals" hint="View & manage" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
</div>
@elseif($isTerminalView)
@php $terminal = auth()->user()->primaryTerminal(); @endphp
<div class="admin-hero mb-8 border-violet-200/60 dark:border-violet-800/40">
    <div class="admin-hero-glow" aria-hidden="true"></div>
    <p class="text-xs font-bold uppercase tracking-widest text-violet-600 dark:text-violet-400">Terminal / Adda Admin</p>
    <h2 class="admin-hero-title mt-1">Welcome, {{ explode(' ', auth()->user()->name)[0] }}</h2>
    <p class="admin-hero-sub">
        @if($terminal)
        Managing <strong class="text-slate-800 dark:text-slate-200">{{ $terminal->name }}</strong> ({{ $terminal->city }}) —
        register bus stands and assign stand users.
        @else
        Contact Super Admin to link a terminal to your account.
        @endif
    </p>
</div>

<div class="admin-quick-grid mb-8">
    <x-admin.quick-action href="{{ route('admin.terminals.my') }}" label="My terminal" hint="Profile & settings" icon="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
    <x-admin.quick-action href="{{ route('admin.bus-stands.create') }}" label="Add bus stand" hint="New counter / stand" icon="M12 4v16m8-8H4" />
    <x-admin.quick-action href="{{ route('admin.terminal-users.index') }}" label="Stand users" hint="Assign stand admins" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1" />
    <x-admin.quick-action href="{{ route('admin.bus-stands.index') }}" label="Bus stands" hint="{{ $stats['total_stands'] }} under you" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
</div>
@else
@php
    $stands = auth()->user()->assignedBusStands()->with('terminal')->orderBy('name')->get();
    $stand = $stands->first();
@endphp
<div class="admin-hero mb-8">
    <div class="admin-hero-glow" aria-hidden="true"></div>
    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400">Operations center</p>
    <h2 class="admin-hero-title mt-1">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h2>
    <p class="admin-hero-sub">
        @if($stands->count() === 1 && $stand)
        Managing <strong class="text-slate-800 dark:text-slate-200">{{ $stand->displayTitle() }}</strong>
        @if($stand->terminal)
        at <strong class="text-slate-800 dark:text-slate-200">{{ $stand->terminal->name }}</strong>
        @endif
        @elseif($stands->count() > 1)
        Managing <strong class="text-slate-800 dark:text-slate-200">{{ $stands->count() }} bus stands</strong> —
        fleet, schedules, and counter sales.
        @else
        Set up vehicles and schedules to start selling tickets.
        @endif
    </p>
</div>

<div class="admin-quick-grid mb-8">
    <x-admin.quick-action href="{{ route('admin.bookings.create') }}" label="New booking" hint="Counter sale" icon="M12 4v16m8-8H4" />
    <x-admin.quick-action href="{{ route('admin.schedules.create') }}" label="Add schedule" hint="Departure & bus" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    <x-admin.quick-action href="{{ route('admin.vehicles.create') }}" label="Add vehicle" hint="Bus & seat map" icon="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
    <x-admin.quick-action href="{{ route('admin.bookings.index') }}" label="Bookings" hint="View & cancel seats" icon="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
</div>
@endif

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-ui.admin-stat-card label="Total bookings" :value="number_format($stats['total_bookings'])" accent="default">
        <x-slot:icon><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></x-slot:icon>
    </x-ui.admin-stat-card>
    <x-ui.admin-stat-card label="Today's bookings" :value="number_format($stats['today_bookings'])" accent="primary" />
    <x-ui.admin-stat-card label="Today's revenue" :value="'PKR '.number_format($stats['today_revenue'])" accent="success" />
    @if($isPlatformView)
    <x-ui.admin-stat-card label="Terminals" :value="number_format($stats['registered_terminals'] ?? 0)" accent="warning" />
    @elseif($isTerminalView)
    <x-ui.admin-stat-card label="Bus stands" :value="number_format($stats['total_stands'])" accent="warning" />
    @else
    <x-ui.admin-stat-card label="Upcoming trips" :value="number_format($stats['upcoming_schedules'])" accent="warning" />
    @endif
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="admin-panel lg:col-span-2">
        <div class="admin-panel-header">
            <h2 class="admin-panel-title">Recent bookings</h2>
            @if(auth()->user()->isBusStandAdmin())
            <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400">View all →</a>
            @endif
        </div>
        @if($stats['recent_bookings']->isNotEmpty())
        <div class="admin-table table-wrap border-0 rounded-none shadow-none">
            <table class="table-modern">
                <thead><tr><th>Booking</th><th>Route</th><th>Date</th><th class="text-right">Amount</th></tr></thead>
                <tbody>
                    @foreach($stats['recent_bookings'] as $booking)
                    <tr>
                        <td>
                            @if(auth()->user()->isBusStandAdmin())
                            <a href="{{ route('admin.bookings.show', $booking->uuid) }}" class="font-mono text-sm font-semibold text-slate-900 hover:text-primary-600 dark:text-white dark:hover:text-primary-400">
                                {{ $booking->booking_number }}
                            </a>
                            @else
                            <span class="font-mono text-sm font-semibold text-slate-900 dark:text-white">{{ $booking->booking_number }}</span>
                            @endif
                            <p class="text-xs text-slate-500">{{ ucfirst($booking->booking_source) }}</p>
                        </td>
                        <td>
                            <span class="admin-route-pill">
                                {{ $booking->schedule->route->departure_city ?? '—' }}
                                <span class="admin-route-arrow">→</span>
                                {{ $booking->schedule->route->destination_city ?? '—' }}
                            </span>
                        </td>
                        <td class="text-sm text-slate-500">{{ $booking->created_at->format('M d, h:i A') }}</td>
                        <td class="text-right font-display font-bold text-primary-600 dark:text-primary-400">PKR {{ number_format($booking->total_amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6">
            <x-ui.empty-state
                title="No bookings yet"
                :description="$isPlatformView ? 'Bookings across all stands will appear here.' : ($isTerminalView ? 'Bookings from your terminal stands will show here.' : 'Create a schedule, then sell tickets from New booking.')"
            >
                @if(auth()->user()->isBusStandAdmin())
                <x-slot:action>
                    <x-ui.button href="{{ route('admin.bookings.create') }}">New booking</x-ui.button>
                </x-slot:action>
                @endif
            </x-ui.empty-state>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h2 class="admin-panel-title">{{ $isPlatformView ? 'Platform snapshot' : ($isTerminalView ? 'Terminal snapshot' : 'Fleet snapshot') }}</h2>
            </div>
            <ul class="space-y-3 p-5">
                @if($isPlatformView)
                @foreach([
                    ['Terminals', $stats['registered_terminals'] ?? 0],
                    ['Bus stands', $stats['registered_stands'] ?? 0],
                    ['Stand admins', $stats['active_stand_admins'] ?? 0],
                    ['Active cities', $stats['active_cities'] ?? 0],
                    ['Active routes', $stats['active_routes']],
                ] as [$label, $value])
                <li class="admin-fleet-stat">
                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $label }}</span>
                    <span class="admin-fleet-stat-value">{{ $value }}</span>
                </li>
                @endforeach
                @elseif($isTerminalView)
                @foreach([
                    ['Bus stands', $stats['total_stands']],
                    ['Active stands', $stats['active_stands'] ?? 0],
                    ['Active routes', $stats['active_routes']],
                    ['Vehicles', $stats['total_vehicles']],
                ] as [$label, $value])
                <li class="admin-fleet-stat">
                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $label }}</span>
                    <span class="admin-fleet-stat-value">{{ $value }}</span>
                </li>
                @endforeach
                <li class="pt-2">
                    <x-ui.button href="{{ route('admin.bus-stands.create') }}" class="w-full">Register bus stand</x-ui.button>
                </li>
                @else
                @foreach([
                    ['Vehicles', $stats['total_vehicles']],
                    ['Active routes', $stats['active_routes']],
                    ['Upcoming trips', $stats['upcoming_schedules']],
                    ['Running now', $stats['running_buses']],
                ] as [$label, $value])
                <li class="admin-fleet-stat">
                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $label }}</span>
                    <span class="admin-fleet-stat-value">{{ $value }}</span>
                </li>
                @endforeach
                <li class="grid grid-cols-2 gap-2 pt-2">
                    <x-ui.button href="{{ route('admin.vehicles.index') }}" variant="secondary" class="!text-xs">Fleet</x-ui.button>
                    <x-ui.button href="{{ route('admin.schedules.index') }}" variant="secondary" class="!text-xs">Schedules</x-ui.button>
                </li>
                @endif
            </ul>
        </div>

        @if(! $isPlatformView && $stats['upcoming_departures']->isNotEmpty())
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h2 class="admin-panel-title">Upcoming departures</h2>
                @if(auth()->user()->isBusStandAdmin())
                <a href="{{ route('admin.schedules.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400">All →</a>
                @endif
            </div>
            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($stats['upcoming_departures'] as $schedule)
                <li class="px-5 py-3">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $schedule->route->departure_city }} → {{ $schedule->route->destination_city }}
                    </p>
                    <p class="mt-0.5 text-xs text-slate-500">
                        {{ $schedule->departure_date->format('M d') }} · {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                        · {{ $schedule->available_seats }} seats
                        @if($schedule->vehicle)
                        · {{ $schedule->vehicle->bus_number }}
                        @endif
                    </p>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection
