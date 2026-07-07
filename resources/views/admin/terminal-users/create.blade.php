@extends('layouts.admin')
@section('title', 'Add Stand User')
@section('header', 'Add Stand User')
@section('breadcrumb', 'New login · '.$terminal->name)

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        title="Add stand user"
        subtitle="Bus Stand Admin — terminal ke saare stands, multiple assign kar sakte hain"
    />

    <form method="POST" action="{{ route('admin.terminal-users.store') }}" class="space-y-6">
        @csrf

        <x-ui.form-section title="Login account" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
            <x-ui.input label="Full name" name="name" :value="old('name')" required />
            <x-ui.input label="Email" name="email" type="email" :value="old('email')" required />
            <x-ui.input label="Mobile" name="phone" type="tel" :value="old('phone')" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Password" name="password" type="password" required autocomplete="new-password" />
                <x-ui.input label="Confirm password" name="password_confirmation" type="password" required autocomplete="new-password" />
            </div>
        </x-ui.form-section>

        <x-ui.form-section
            title="Assign bus stands (optional)"
            description="Saare terminal stands — multiple users same stand share kar sakte hain."
            icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"
        >
            <x-admin.stand-assignment-list :stands="$terminalStands" />
        </x-ui.form-section>

        <div class="flex gap-3">
            <x-ui.button type="submit">Create user</x-ui.button>
            <x-ui.button href="{{ route('admin.terminal-users.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
