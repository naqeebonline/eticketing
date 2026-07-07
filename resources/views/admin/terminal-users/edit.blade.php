@extends('layouts.admin')
@section('title', 'Edit Stand User')
@section('header', 'Edit Stand User')
@section('breadcrumb', $terminalUser->name)

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header title="Edit user & stands" :subtitle="$terminalUser->email" />

    <form method="POST" action="{{ route('admin.terminal-users.update', $terminalUser) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.form-section title="Login account" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
            <x-ui.input label="Full name" name="name" :value="old('name', $terminalUser->name)" required />
            <x-ui.input label="Email" name="email" type="email" :value="old('email', $terminalUser->email)" required />
            <x-ui.input label="Mobile" name="phone" type="tel" :value="old('phone', $terminalUser->phone)" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="New password" name="password" type="password" hint="Leave blank to keep" autocomplete="new-password" />
                <x-ui.input label="Confirm password" name="password_confirmation" type="password" autocomplete="new-password" />
            </div>
        </x-ui.form-section>

        <x-ui.form-section
            title="Assigned bus stands"
            description="Terminal ke saare stands — ek stand multiple users ko assign ho sakta hai."
            icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"
        >
            <x-admin.stand-assignment-list
                :stands="$terminalStands"
                :assigned-ids="$assignedIds"
            />
        </x-ui.form-section>

        <div class="flex gap-3">
            <x-ui.button type="submit">Save changes</x-ui.button>
            <x-ui.button href="{{ route('admin.terminal-users.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
