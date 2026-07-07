@extends('layouts.admin')
@section('title', auth()->user()->isTerminalAdmin() ? 'My Terminal' : 'Edit Terminal')
@section('header', auth()->user()->isTerminalAdmin() ? 'My Terminal / Adda' : 'Edit Terminal / Adda')
@section('breadcrumb', $terminal->name)

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        :title="auth()->user()->isTerminalAdmin() ? 'My terminal / adda' : 'Edit terminal'"
        :subtitle="$terminal->city"
    />

    <form method="POST" action="{{ route('admin.terminals.update', $terminal) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.form-section title="Terminal details" icon="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
            <x-ui.input label="Terminal name" name="name" :value="old('name', $terminal->name)" required />
            <x-ui.city-select label="City" name="city" :value="old('city', $terminal->city)" required />
            <x-ui.textarea label="Address" name="address" rows="2">{{ old('address', $terminal->address) }}</x-ui.textarea>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone', $terminal->phone)" />
                <x-ui.input label="Email" name="email" type="email" :value="old('email', $terminal->email)" />
            </div>
            <x-ui.input label="Sort order" name="sort_order" type="number" min="0" :value="old('sort_order', $terminal->sort_order)" />
            @if(auth()->user()->isSuperAdmin())
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/50">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $terminal->is_active)) class="rounded border-slate-300 text-primary-600">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
            </label>
            @endif
        </x-ui.form-section>

        @php
            $canEditOwner = $terminal->owner && (
                auth()->user()->isSuperAdmin()
                || (auth()->user()->isTerminalAdmin() && $terminal->owner_id === auth()->id())
            );
        @endphp
        @if($canEditOwner)
        <x-ui.form-section
            :accent="true"
            title="{{ auth()->user()->isTerminalAdmin() ? 'Your login account' : 'Terminal / Adda Admin login' }}"
            description="Role: Terminal / Adda Admin"
            icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
        >
            <x-ui.input label="{{ auth()->user()->isTerminalAdmin() ? 'Your name' : 'Admin name' }}" name="owner_name" :value="old('owner_name', $terminal->owner->name)" required />
            <x-ui.input label="Login email" name="owner_email" type="email" :value="old('owner_email', $terminal->owner->email)" required />
            <x-ui.input label="Mobile" name="owner_phone" type="tel" :value="old('owner_phone', $terminal->owner->phone)" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="New password" name="owner_password" type="password" autocomplete="new-password" hint="Leave blank to keep current" />
                <x-ui.input label="Confirm password" name="owner_password_confirmation" type="password" autocomplete="new-password" />
            </div>
        </x-ui.form-section>
        @endif

        <div class="flex gap-3">
            <x-ui.button type="submit">Save changes</x-ui.button>
            <x-ui.button href="{{ auth()->user()->isTerminalAdmin() ? route('admin.dashboard') : route('admin.terminals.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
