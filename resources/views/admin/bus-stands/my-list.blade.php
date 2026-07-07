@extends('layouts.admin')
@section('title', 'My Bus Stands')
@section('header', 'My Bus Stands')
@section('breadcrumb', 'Your assigned counters')

@section('content')
@php
    $totalVehicles = $stands->sum('vehicles_count');
    $totalRoutes = $stands->sum('active_routes_count');
@endphp

<div class="admin-hero mb-8">
    <div class="admin-hero-glow" aria-hidden="true"></div>
    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400">Your workspace</p>
    <h2 class="admin-hero-title mt-1">{{ $stands->count() }} bus stand{{ $stands->count() === 1 ? '' : 's' }}</h2>
    <p class="admin-hero-sub">
        Har card ek physical counter hai — routes alag module mein. Fleet aur bookings yahan se manage karein.
    </p>
</div>

<div class="mb-8 grid gap-4 sm:grid-cols-3">
    <x-ui.admin-stat-card label="Stands" :value="(string) $stands->count()" accent="primary" />
    <x-ui.admin-stat-card label="Vehicles" :value="(string) $totalVehicles" accent="default" />
    <x-ui.admin-stat-card label="Active routes" :value="(string) $totalRoutes" accent="warning" />
</div>

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @foreach($stands as $stand)
    <a
        href="{{ route('admin.bus-stands.edit', $stand) }}"
        class="my-stand-card group"
    >
        <div class="my-stand-card-header">
            <div class="my-stand-card-icon" aria-hidden="true">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <x-ui.badge :variant="$stand->is_active ? 'success' : 'neutral'">
                {{ $stand->is_active ? 'Active' : 'Inactive' }}
            </x-ui.badge>
        </div>

        <h3 class="my-stand-card-title">{{ $stand->displayTitle() }}</h3>

        @if($stand->hasLegacyRouteStyleName() && $stand->name !== $stand->displayTitle())
        <p class="my-stand-card-legacy text-xs text-slate-400 line-through decoration-slate-300">{{ $stand->name }}</p>
        @endif

        <p class="my-stand-card-address">{{ $stand->address }}</p>

        @if($stand->terminal)
        <p class="my-stand-card-terminal">{{ $stand->terminal->name }} · {{ $stand->city }}</p>
        @endif

        <div class="my-stand-card-stats">
            <span>{{ $stand->vehicles_count }} vehicles</span>
            <span class="my-stand-card-dot">·</span>
            <span>{{ $stand->active_routes_count }} routes</span>
        </div>

        <span class="my-stand-card-cta">
            Open stand
            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    </a>
    @endforeach
</div>
@endsection
