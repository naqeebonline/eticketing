@extends('layouts.admin')
@section('title', 'Add Terminal')
@section('header', 'Add Terminal / Adda')
@section('breadcrumb', 'Platform · terminal + login account')

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        title="Add terminal / adda"
        subtitle="Super Admin creates the terminal and a dedicated Terminal / Adda Admin login"
    />

    <form method="POST" action="{{ route('admin.terminals.store') }}" class="space-y-6">
        @csrf

        <x-ui.form-section title="Terminal details" description="Physical terminal or adda location." icon="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
            <x-ui.input label="Terminal name" name="name" :value="old('name')" required placeholder="e.g. Saddar Bus Terminal" />
            <x-ui.city-select label="City" name="city" :value="old('city')" required />
            <x-ui.textarea label="Address" name="address" rows="2">{{ old('address') }}</x-ui.textarea>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone')" />
                <x-ui.input label="Email" name="email" type="email" :value="old('email')" />
            </div>
            <x-ui.input label="Sort order" name="sort_order" type="number" min="0" :value="old('sort_order', 0)" />
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/50">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-primary-600">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
            </label>
        </x-ui.form-section>

        <x-ui.form-section
            :accent="true"
            title="Terminal / Adda Admin login"
            description="Yeh account terminal manage karega aur is ke under bus stands register karega."
            icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
        >
            <x-ui.input label="Admin full name" name="owner_name" :value="old('owner_name')" required />
            <x-ui.input label="Login email" name="owner_email" type="email" :value="old('owner_email')" required />
            <x-ui.input label="Mobile" name="owner_phone" type="tel" :value="old('owner_phone')" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Password" name="owner_password" type="password" required autocomplete="new-password" />
                <x-ui.input label="Confirm password" name="owner_password_confirmation" type="password" required autocomplete="new-password" />
            </div>
        </x-ui.form-section>

        <div class="flex gap-3">
            <x-ui.button type="submit">Create terminal & account</x-ui.button>
            <x-ui.button href="{{ route('admin.terminals.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
