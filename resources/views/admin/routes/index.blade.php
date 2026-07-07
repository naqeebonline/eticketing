@extends('layouts.admin')
@section('title', 'Routes')
@section('header', 'Routes')
@section('breadcrumb', 'Per bus stand · city pairs')

@section('content')
<x-ui.page-header title="Routes" subtitle="Har route ek bus stand se linked — schedules sirf matching fleet use karti hain">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.routes.create', request()->only('bus_stand_id')) }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add route
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

@if($busStands->isNotEmpty())
<form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
    <div class="min-w-[14rem]">
        <label class="form-label">Filter by bus stand</label>
        <select name="bus_stand_id" class="input-field" onchange="this.form.submit()">
            <option value="">All stands</option>
            @foreach($busStands as $stand)
            <option value="{{ $stand->id }}" @selected((int) request('bus_stand_id') === $stand->id)>
                {{ $stand->displayTitle() }}@if($stand->terminal) — {{ $stand->terminal->name }}@endif
            </option>
            @endforeach
        </select>
    </div>
</form>
@endif

<div class="admin-panel admin-table">
    <div class="admin-panel-header">
        <p class="text-sm text-slate-500">{{ $routes->total() }} route{{ $routes->total() === 1 ? '' : 's' }}</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Bus stand</th>
                    <th>Cities</th>
                    <th>Distance</th>
                    <th>Base fare</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routes as $route)
                <tr>
                    <td class="font-semibold text-slate-900 dark:text-white">{{ $route->name }}</td>
                    <td class="text-sm text-slate-600 dark:text-slate-400">{{ $route->busStand?->displayTitle() ?? '—' }}</td>
                    <td>
                        <span class="admin-route-pill">
                            {{ $route->departure_city }}
                            <span class="admin-route-arrow">→</span>
                            {{ $route->destination_city }}
                        </span>
                    </td>
                    <td>{{ $route->distance_km ? number_format($route->distance_km).' km' : '—' }}</td>
                    <td class="font-display font-bold text-primary-600 dark:text-primary-400">PKR {{ number_format($route->base_fare) }}</td>
                    <td><x-ui.badge :variant="$route->is_active ? 'success' : 'neutral'">{{ $route->is_active ? 'Active' : 'Inactive' }}</x-ui.badge></td>
                    <td class="text-right">
                        <a href="{{ route('admin.routes.edit', $route) }}" class="admin-row-action">
                            Edit
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <x-ui.empty-state title="No routes yet" description="Pehle bus stand select karein, phir us stand ke liye route add karein.">
                        <x-slot:action><x-ui.button href="{{ route('admin.routes.create') }}">Add route</x-ui.button></x-slot:action>
                    </x-ui.empty-state>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($routes->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $routes->links() }}</div>
    @endif
</div>
@endsection
