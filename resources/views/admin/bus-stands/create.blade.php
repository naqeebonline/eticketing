@extends('layouts.admin')
@section('title', 'Add Bus Stand')
@section('header', 'Add Bus Stand')
@section('breadcrumb', 'Counter / location under terminal')

@section('content')
@php
    $oldRoutes = old('routes');
    $initialRoutes = $oldRoutes
        ? array_values(array_map(fn ($route) => [
            'destination_city' => $route['destination_city'] ?? '',
            'base_fare' => $route['base_fare'] ?? '',
            'distance_km' => $route['distance_km'] ?? '',
            'duration_minutes' => $route['duration_minutes'] ?? '',
        ], $oldRoutes))
        : [['destination_city' => '', 'base_fare' => '', 'distance_km' => '', 'duration_minutes' => '']];

    $selectedTerminalId = old('terminal_id', $defaultTerminalId ?? '');
    $selectedFromCity = $terminalsMap[$selectedTerminalId] ?? ($defaultFromCity ?? '');
@endphp

<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        title="Add bus stand"
        subtitle="Stand details aur routes ek hi page par add karein."
    />

    @if($noTerminals)
    <div class="admin-panel p-6">
        <p class="text-sm text-slate-600 dark:text-slate-400">Pehle active terminal add karein.</p>
        <x-ui.button href="{{ route('admin.terminals.index') }}" class="mt-4">Manage terminals</x-ui.button>
    </div>
    @else
    <div
        x-data="busStandForm({
            terminalsMap: @js($terminalsMap ?? []),
            usersByTerminal: @js($usersByTerminal ?? []),
            terminalId: @js((string) $selectedTerminalId),
            fromCity: @js($selectedFromCity),
            cities: @js($cities ?? []),
            initialRoutes: @js($initialRoutes),
            selectedUserIds: @js(array_map('intval', old('user_ids', []))),
        })"
    >
    <form method="POST" action="{{ route('admin.bus-stands.store') }}" class="space-y-6">
        @csrf

        <x-ui.form-section title="Terminal / Adda" icon="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
            @if($isTerminalAdmin)
            <input type="hidden" name="terminal_id" value="{{ $defaultTerminalId }}">
            <div class="rounded-xl border border-violet-200 bg-violet-50/80 px-4 py-3 dark:border-violet-800/50 dark:bg-violet-950/40">
                <p class="text-xs font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400">Your terminal</p>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $defaultFromCity }}</p>
            </div>
            @else
            <x-ui.terminal-select
                :terminals="$terminals"
                :value="$selectedTerminalId"
                required
                x-on:change="onTerminalChange($event.target.value)"
            />
            @endif
        </x-ui.form-section>

        <x-ui.form-section title="Stand details" description="Physical counter location aur contact details." icon="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
            <x-ui.input label="Stand name" name="name" :value="old('name')" required placeholder="e.g. Haji Camp Counter 1" />
            <x-ui.select label="Type" name="type" required>
                <option value="company" @selected(old('type', 'company') === 'company')>Company</option>
                <option value="individual" @selected(old('type') === 'individual')>Individual</option>
            </x-ui.select>
            <x-ui.textarea label="Address / location" name="address" required rows="2" placeholder="e.g. M.A. Jinnah Road counter, Saddar">{{ old('address') }}</x-ui.textarea>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone')" />
                <x-ui.input label="Email" name="email" type="email" :value="old('email')" />
            </div>
        </x-ui.form-section>

        <x-admin.bus-stand-routes-create-editor :cities="$cities" />

        <x-admin.bus-stand-user-picker :show-stand-users-link="$isTerminalAdmin" />

        <div class="flex flex-wrap gap-3">
            <x-ui.button type="submit">Create bus stand</x-ui.button>
            <x-ui.button href="{{ route('admin.bus-stands.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
    </div>
    @endif
</div>
@endsection
