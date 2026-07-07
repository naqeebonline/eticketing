@extends('layouts.admin')
@section('title', 'Bus Stands')
@section('header', 'Bus Stands')
@section('breadcrumb', 'Platform · all registered stands')

@section('content')
<x-ui.page-header
    title="{{ auth()->user()->isTerminalAdmin() ? 'Bus stands at my terminal(s)' : 'All bus stands' }}"
    subtitle="Har stand ki apni routes — Schedules sirf us stand ki route + fleet use karti hain"
>
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isTerminalAdmin())
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.bus-stands.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Register stand
        </x-ui.button>
    </x-slot:actions>
    @endif
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="admin-panel-header">
        <p class="text-sm text-slate-500">{{ $stands->total() }} stand{{ $stands->total() === 1 ? '' : 's' }} on the platform</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Stand</th>
                    <th>Terminal</th>
                    <th>Location</th>
                    <th>Routes</th>
                    <th>Type</th>
                    <th>Assigned users</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stands as $stand)
                <tr>
                    <td class="font-semibold text-slate-900 dark:text-white">{{ $stand->displayTitle() }}</td>
                    <td class="text-slate-600 dark:text-slate-400">{{ $stand->terminal?->name ?? '—' }}</td>
                    <td class="text-sm text-slate-500 max-w-[12rem] truncate" title="{{ $stand->address }}">{{ $stand->address }}</td>
                    <td>
                        @if($stand->routes->isNotEmpty())
                        <div class="flex flex-col gap-1">
                            @foreach($stand->routes as $route)
                            <span class="admin-route-pill text-xs">
                                {{ $route->departure_city }}
                                <span class="admin-route-arrow">→</span>
                                {{ $route->destination_city }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <a href="{{ route('admin.routes.create', ['bus_stand_id' => $stand->id]) }}" class="text-xs font-semibold text-primary-600 hover:underline">+ Add route</a>
                        @endif
                    </td>
                    <td><x-ui.badge variant="neutral">{{ ucfirst($stand->type) }}</x-ui.badge></td>
                    <td>
                        @php
                            $assignedUsers = $stand->assignedUsers;
                            if ($assignedUsers->isEmpty() && $stand->owner) {
                                $assignedUsers = collect([$stand->owner]);
                            }
                        @endphp
                        @if($assignedUsers->isNotEmpty())
                        <div class="space-y-2 text-sm">
                            @foreach($assignedUsers as $user)
                            <div>
                                <p class="font-medium text-slate-800 dark:text-slate-200">{{ $user->name }}</p>
                                <p class="text-slate-500">{{ $user->email }}</p>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <x-ui.badge variant="warning">Unassigned</x-ui.badge>
                        @endif
                    </td>
                    <td>
                        <x-ui.badge :variant="$stand->is_active ? 'success' : 'neutral'">
                            {{ $stand->is_active ? 'Active' : 'Inactive' }}
                        </x-ui.badge>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.bus-stands.edit', $stand) }}" class="admin-row-action">
                            Edit
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <x-ui.empty-state title="No bus stands yet" description="Register a stand, then add routes for that stand.">
                            <x-slot:action><x-ui.button href="{{ route('admin.bus-stands.create') }}">Register stand</x-ui.button></x-slot:action>
                        </x-ui.empty-state>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stands->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $stands->links() }}</div>
    @endif
</div>
@endsection
