@extends('layouts.admin')
@section('title', 'Terminals / Adda')
@section('header', 'Terminals / Adda')
@section('breadcrumb', 'Platform · step 2 — before bus stands')

@section('content')
<x-ui.page-header title="Terminals / Adda" subtitle="Super Admin adds terminals first; then register bus stands under each terminal">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.terminals.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add terminal
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="admin-panel-header">
        <p class="text-sm text-slate-500">{{ $terminals->total() }} {{ Str::plural('terminal', $terminals->total()) }} · bus stands are registered under these</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Terminal / Adda</th>
                    <th>City</th>
                    <th>Terminal Admin</th>
                    <th>Bus stands</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($terminals as $terminal)
                <tr>
                    <td class="font-semibold text-slate-900 dark:text-white">{{ $terminal->name }}</td>
                    <td>{{ $terminal->city }}</td>
                    <td>
                        @if($terminal->owner)
                        <div class="text-sm">
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $terminal->owner->name }}</p>
                            <p class="text-slate-500">{{ $terminal->owner->email }}</p>
                        </div>
                        @else
                        <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td>{{ $terminal->bus_stands_count }}</td>
                    <td>
                        <x-ui.badge :variant="$terminal->is_active ? 'success' : 'neutral'">
                            {{ $terminal->is_active ? 'Active' : 'Inactive' }}
                        </x-ui.badge>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.terminals.edit', $terminal) }}" class="admin-row-action">Edit</a>
                        <form method="POST" action="{{ route('admin.terminals.destroy', $terminal) }}" class="inline" data-confirm="Remove this terminal? Linked bus stands must be cleared first." data-confirm-title="Remove terminal" data-confirm-variant="danger" data-confirm-label="Remove">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-row-action text-danger-600 hover:bg-danger-50 dark:text-danger-400">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-ui.empty-state title="No terminals yet" description="Add a terminal / adda before registering bus stands.">
                            <x-slot:action><x-ui.button href="{{ route('admin.terminals.create') }}">Add terminal</x-ui.button></x-slot:action>
                        </x-ui.empty-state>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($terminals->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $terminals->links() }}</div>
    @endif
</div>
@endsection
