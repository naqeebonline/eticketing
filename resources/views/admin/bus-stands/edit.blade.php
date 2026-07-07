@extends('layouts.admin')
@section('title', auth()->user()->isBusStandAdmin() ? 'My Bus Stand' : 'Edit Bus Stand')
@section('header', auth()->user()->isBusStandAdmin() ? 'My Bus Stand' : 'Edit Bus Stand')
@section('breadcrumb', $busStand->displayTitle())

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        :title="auth()->user()->isBusStandAdmin() ? 'My bus stand' : 'Edit bus stand'"
        :subtitle="$busStand->displaySubtitle()"
    />

    @if(auth()->user()->isBusStandAdmin() && auth()->user()->assignedBusStands()->count() > 1)
    <div class="mb-6">
        <a href="{{ route('admin.bus-stands.my') }}" class="text-sm font-semibold text-primary-600 hover:underline">← All my stands</a>
    </div>
    @endif

    @if(auth()->user()->isBusStandAdmin())
    <form method="POST" action="{{ route('admin.bus-stands.update', $busStand) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="admin-panel overflow-hidden p-0">
            <div class="border-b border-slate-100 bg-gradient-to-r from-primary-50 to-white px-5 py-4 dark:border-slate-800 dark:from-primary-950/50 dark:to-slate-900">
                <p class="text-xs font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400">Stand</p>
                <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $busStand->displayTitle() }}</p>
                @if($busStand->terminal)
                <p class="mt-1 text-sm text-slate-500">{{ $busStand->terminal->name }} · {{ $busStand->city }}</p>
                @endif
            </div>
            <div class="grid gap-3 px-5 py-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs text-slate-500">Terminal</p>
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $busStand->terminal?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Vehicles / routes</p>
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $busStand->vehicles_count }} · {{ $busStand->active_routes_count }} active</p>
                </div>
            </div>
        </div>

        <x-ui.form-section title="Stand details" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5">
            <x-ui.input label="Stand name" name="name" :value="old('name', $busStand->editableName())" required placeholder="e.g. Haji Camp Counter 1" />
            @if($busStand->hasLegacyRouteStyleName())
            <p class="form-hint -mt-2">Purana route-style naam replace karein — counter ka asal naam likhein (abhi: {{ $busStand->name }}).</p>
            @endif
            <x-ui.select label="Type" name="type" required>
                <option value="company" @selected(old('type', $busStand->type) === 'company')>Company</option>
                <option value="individual" @selected(old('type', $busStand->type) === 'individual')>Individual</option>
            </x-ui.select>
            <x-ui.textarea label="Address / location" name="address" required rows="2">{{ old('address', $busStand->address) }}</x-ui.textarea>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone', $busStand->phone)" />
                <x-ui.input label="Email" name="email" type="email" :value="old('email', $busStand->email)" />
            </div>
        </x-ui.form-section>

        <x-admin.bus-stand-routes-editor :bus-stand="$busStand" :routes="$busStand->routes" />

        <div class="grid gap-3 sm:grid-cols-2">
            <a href="{{ route('admin.vehicles.index') }}" class="admin-panel flex items-center gap-3 p-4 transition hover:border-primary-300 dark:hover:border-primary-700">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100 text-primary-600 dark:bg-primary-900/40">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">Manage vehicles</span>
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="admin-panel flex items-center gap-3 p-4 transition hover:border-primary-300 dark:hover:border-primary-700">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-900/40">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">Schedules</span>
            </a>
        </div>

        @php
            $canEditOwnerAccount = $busStand->owner && auth()->user()->ownsBusStand($busStand->id);
        @endphp
        @if($canEditOwnerAccount)
        <x-ui.form-section :accent="true" title="Your login" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
            <x-ui.input label="Name" name="owner_name" :value="old('owner_name', $busStand->owner->name)" required />
            <x-ui.input label="Email" name="owner_email" type="email" :value="old('owner_email', $busStand->owner->email)" required />
            <x-ui.input label="Mobile" name="owner_phone" type="tel" :value="old('owner_phone', $busStand->owner->phone)" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="New password" name="owner_password" type="password" hint="Leave blank to keep" />
                <x-ui.input label="Confirm" name="owner_password_confirmation" type="password" />
            </div>
        </x-ui.form-section>
        @endif

        <div class="flex gap-3">
            <x-ui.button type="submit">Save changes</x-ui.button>
            <x-ui.button href="{{ route('admin.bus-stands.my') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
    @else
    <div
        class="space-y-6"
        x-data="busStandForm({
            terminalsMap: @js($terminalsMap ?? []),
            usersByTerminal: @js($usersByTerminal ?? []),
            terminalId: @js((string) old('terminal_id', $busStand->terminal_id)),
            fromCity: @js(old('terminal_id', $busStand->terminal_id) ? ($terminalsMap[old('terminal_id', $busStand->terminal_id)] ?? $busStand->city) : $busStand->city),
            selectedUserIds: @js(array_map('intval', old('user_ids', $assignedUserIds ?? []))),
        })"
    >
        <form method="POST" action="{{ route('admin.bus-stands.update', $busStand) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-ui.form-section title="Terminal / Adda" icon="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                @if($isTerminalAdmin)
                <input type="hidden" name="terminal_id" value="{{ $busStand->terminal_id }}">
                <div class="rounded-xl border border-violet-200 bg-violet-50/80 px-4 py-3 dark:border-violet-800/50 dark:bg-violet-950/40">
                    <p class="text-xs font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400">Terminal</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $busStand->terminal?->name }} · {{ $busStand->city }}</p>
                </div>
                @else
                <x-ui.terminal-select
                    :terminals="$terminals"
                    :value="old('terminal_id', $busStand->terminal_id)"
                    required
                    x-on:change="onTerminalChange($event.target.value)"
                />
                @endif
            </x-ui.form-section>

            <x-ui.form-section title="Stand details" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5">
                <x-ui.input label="Stand name" name="name" :value="old('name', $busStand->editableName())" required placeholder="e.g. Haji Camp Counter 1" />
                @if($busStand->hasLegacyRouteStyleName())
                <p class="form-hint -mt-2">Purana route-style naam replace karein — counter ka asal naam likhein (abhi: {{ $busStand->name }}).</p>
                @endif
                <x-ui.select label="Type" name="type" required>
                    <option value="company" @selected(old('type', $busStand->type) === 'company')>Company</option>
                    <option value="individual" @selected(old('type', $busStand->type) === 'individual')>Individual</option>
                </x-ui.select>
                <x-ui.textarea label="Address / location" name="address" required rows="2">{{ old('address', $busStand->address) }}</x-ui.textarea>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone', $busStand->phone)" />
                    <x-ui.input label="Email" name="email" type="email" :value="old('email', $busStand->email)" />
                </div>
                <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/50">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $busStand->is_active)) class="rounded border-slate-300 text-primary-600">
                    <span class="text-sm font-medium">Active</span>
                </label>
            </x-ui.form-section>

            <x-admin.bus-stand-user-picker :show-stand-users-link="true" />

            <x-admin.bus-stand-routes-editor :bus-stand="$busStand" :routes="$busStand->routes" />

            <div class="flex flex-wrap gap-3">
                <x-ui.button type="submit">Save changes</x-ui.button>
                <x-ui.button href="{{ route('admin.bus-stands.index') }}" variant="secondary">Cancel</x-ui.button>
            </div>
        </form>

        @if(auth()->user()->isSuperAdmin())
        <form method="POST" action="{{ route('admin.bus-stands.destroy', $busStand) }}" class="mt-4 border-t border-slate-200 pt-4 dark:border-slate-800" data-confirm="Delete this bus stand? This cannot be undone." data-confirm-title="Delete bus stand" data-confirm-variant="danger" data-confirm-label="Delete">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="secondary" class="!text-red-600 hover:!bg-red-50 dark:hover:!bg-red-950/30">Delete bus stand</x-ui.button>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
