@extends('layouts.admin')
@section('title', 'Stand Users')
@section('header', 'Stand Users')
@section('breadcrumb', $terminal->name.' · assign stands to users')

@section('content')
<x-ui.page-header
    title="Stand users"
    subtitle="Har user ko multiple bus stands — ek stand multiple users ko bhi assign ho sakta hai"
>
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.terminal-users.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add user
        </x-ui.button>
        <x-ui.button href="{{ route('admin.bus-stands.create') }}" variant="secondary">Add bus stand</x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Assigned stands</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $u->name }}</p>
                        <p class="text-sm text-slate-500">{{ $u->email }}</p>
                    </td>
                    <td>
                        @if($u->assignedBusStands->isNotEmpty())
                        <ul class="space-y-1 text-sm">
                            @foreach($u->assignedBusStands as $s)
                            <li>
                                <span class="font-medium">{{ $s->displayTitle() }}</span>
                                <span class="text-slate-400 text-xs block">{{ $s->address }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <span class="text-slate-400 text-sm">No stands assigned</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.terminal-users.edit', $u) }}" class="admin-row-action">Edit & assign stands</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">
                        <x-ui.empty-state title="No stand users yet" description="Create Bus Stand Admin users and assign them one or more stands.">
                            <x-slot:action><x-ui.button href="{{ route('admin.terminal-users.create') }}">Add user</x-ui.button></x-slot:action>
                        </x-ui.empty-state>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
