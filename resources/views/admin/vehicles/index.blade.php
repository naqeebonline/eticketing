@extends('layouts.admin')
@section('title', 'Vehicles')
@section('header', 'Vehicles')
@section('breadcrumb', 'Fleet management')

@section('content')
<x-ui.page-header title="Fleet" subtitle="Buses, drivers, owners, and conductors">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.vehicles.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add vehicle
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="admin-panel-header">
        <p class="text-sm text-slate-500">{{ $vehicles->total() }} vehicle{{ $vehicles->total() === 1 ? '' : 's' }} registered</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Bus</th>
                    <th>Owner</th>
                    <th>Driver</th>
                    <th>Conductors</th>
                    <th>Stand</th>
                    <th>Seats</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <span class="admin-avatar admin-avatar--bus">{{ strtoupper(substr($vehicle->name, 0, 1)) }}</span>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $vehicle->name }}</p>
                                <p class="text-xs text-slate-500">{{ $vehicle->bus_number }} · {{ $vehicle->registration_number }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $vehicle->owner_name ?? '—' }}</p>
                        @if($vehicle->owner_phone)
                        <p class="text-xs text-slate-500">{{ $vehicle->owner_phone }}</p>
                        @endif
                    </td>
                    <td>
                        @if($vehicle->driver)
                        <p class="text-sm font-medium">{{ $vehicle->driver->displayName() }}</p>
                        @if($vehicle->driver->phone)
                        <p class="text-xs text-slate-500">{{ $vehicle->driver->phone }}</p>
                        @endif
                        @else
                        <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td>
                        @if($vehicle->conductors->isNotEmpty())
                        <ul class="space-y-0.5 text-sm">
                            @foreach($vehicle->conductors as $conductor)
                            <li>
                                {{ $conductor->displayName() }}
                                @if($conductor->pivot->is_primary)
                                <x-ui.badge variant="primary" class="!text-[10px]">Primary</x-ui.badge>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $vehicle->busStand->name ?? '—' }}</td>
                    <td><span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold tabular-nums dark:bg-slate-800">{{ $vehicle->total_seats }}</span></td>
                    <td><x-ui.badge :variant="$vehicle->is_active ? 'success' : 'neutral'">{{ $vehicle->is_active ? 'Active' : 'Inactive' }}</x-ui.badge></td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <x-ui.empty-state title="No vehicles yet" description="Register your first bus with driver, owner, and seat layout.">
                        <x-slot:action><x-ui.button href="{{ route('admin.vehicles.create') }}">Add vehicle</x-ui.button></x-slot:action>
                    </x-ui.empty-state>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vehicles->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $vehicles->links() }}</div>
    @endif
</div>
@endsection
