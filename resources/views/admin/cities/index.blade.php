@extends('layouts.admin')
@section('title', 'Cities')
@section('header', 'Cities')
@section('breadcrumb', 'Platform · approved cities list')

@section('content')
<x-ui.page-header title="Cities" subtitle="Only these cities appear for Bus Stand Admins and on the public booking site">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.cities.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add city
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="admin-panel-header">
        <p class="text-sm text-slate-500">{{ $cities->total() }} {{ Str::plural('city', $cities->total()) }} · inactive cities are hidden from booking</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                <tr>
                    <td class="font-semibold text-slate-900 dark:text-white">{{ $city->name }}</td>
                    <td>{{ $city->sort_order }}</td>
                    <td>
                        <x-ui.badge :variant="$city->is_active ? 'success' : 'neutral'">
                            {{ $city->is_active ? 'Active' : 'Inactive' }}
                        </x-ui.badge>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.cities.edit', $city) }}" class="admin-row-action">Edit</a>
                        <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" class="inline" data-confirm="Remove this city? It will no longer appear in route options." data-confirm-title="Remove city" data-confirm-variant="danger" data-confirm-label="Remove">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-row-action text-danger-600 hover:bg-danger-50 dark:text-danger-400">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <x-ui.empty-state title="No cities yet" description="Add cities that Bus Stand Admins and passengers can use.">
                            <x-slot:action><x-ui.button href="{{ route('admin.cities.create') }}">Add city</x-ui.button></x-slot:action>
                        </x-ui.empty-state>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cities->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $cities->links() }}</div>
    @endif
</div>
@endsection
