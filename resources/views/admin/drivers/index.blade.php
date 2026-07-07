@extends('layouts.admin')
@section('title', 'Drivers')
@section('header', 'Drivers')
@section('breadcrumb', 'Fleet · drivers')

@section('content')
<x-ui.page-header title="Drivers" subtitle="Add drivers here — assign to vehicles or per-schedule later">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.drivers.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add driver
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="admin-panel-header">
        <p class="text-sm text-slate-500">{{ $drivers->total() }} driver{{ $drivers->total() === 1 ? '' : 's' }}</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>License</th>
                    <th>Bus stand</th>
                    <th>Default vehicle</th>
                    <th>Schedules</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr>
                    <td>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $driver->displayName() }}</p>
                        @if($driver->phone)
                        <p class="text-xs text-slate-500">{{ $driver->phone }}</p>
                        @endif
                    </td>
                    <td class="text-sm">
                        <p class="font-mono text-slate-700 dark:text-slate-300">{{ $driver->license_number }}</p>
                        <p class="text-xs text-slate-500">Exp {{ $driver->license_expiry->format('M d, Y') }}</p>
                    </td>
                    <td class="text-sm text-slate-600 dark:text-slate-400">
                        {{ $driver->busStand?->name ?? '—' }}
                    </td>
                    <td class="text-sm">
                        @if($driver->vehicles->isNotEmpty())
                        {{ $driver->vehicles->pluck('name')->join(', ') }}
                        @else
                        <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="tabular-nums text-sm">{{ $driver->schedules_count }}</td>
                    <td>
                        <x-ui.badge :variant="$driver->is_active ? 'success' : 'neutral'">
                            {{ $driver->is_active ? 'Active' : 'Inactive' }}
                        </x-ui.badge>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.drivers.edit', $driver) }}" class="admin-row-action">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <x-ui.empty-state title="No drivers yet" description="Add drivers independently, then pick them on vehicles or schedules.">
                            <x-slot:action><x-ui.button href="{{ route('admin.drivers.create') }}">Add driver</x-ui.button></x-slot:action>
                        </x-ui.empty-state>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($drivers->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $drivers->links() }}</div>
    @endif
</div>
@endsection
