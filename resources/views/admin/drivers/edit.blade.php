@extends('layouts.admin')
@section('title', 'Edit Driver')
@section('header', 'Edit Driver')
@section('breadcrumb', $driver->displayName())

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header title="Edit driver" :subtitle="$driver->displayName()" />

    <form method="POST" action="{{ route('admin.drivers.update', $driver) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.form-section title="Stand" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5">
            <x-ui.select label="Bus stand" name="bus_stand_id" required>
                @foreach($busStands as $stand)
                <option value="{{ $stand->id }}" @selected(old('bus_stand_id', $driver->bus_stand_id) == $stand->id)>
                    {{ $stand->displayTitle() }}
                    @if($stand->terminal) — {{ $stand->terminal->name }}@endif
                </option>
                @endforeach
            </x-ui.select>
        </x-ui.form-section>

        <x-ui.form-section title="Personal details" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
            <x-ui.input label="Full name" name="name" required :value="old('name', $driver->name)" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone', $driver->phone)" />
                <x-ui.input label="CNIC" name="cnic" :value="old('cnic', $driver->cnic)" />
            </div>
            <x-ui.textarea label="Address" name="address" rows="2">{{ old('address', $driver->address) }}</x-ui.textarea>
            <x-ui.input label="Emergency contact" name="emergency_contact" type="tel" :value="old('emergency_contact', $driver->emergency_contact)" />
        </x-ui.form-section>

        <x-ui.form-section title="License" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            <x-ui.input label="License number" name="license_number" required :value="old('license_number', $driver->license_number)" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="License expiry" name="license_expiry" type="date" required :value="old('license_expiry', $driver->license_expiry->format('Y-m-d'))" />
                <x-ui.input label="License class" name="license_class" :value="old('license_class', $driver->license_class)" />
            </div>
        </x-ui.form-section>

        @if($driver->vehicles->isNotEmpty())
        <div class="rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-800/40">
            <p class="font-medium text-slate-700 dark:text-slate-300">Default vehicle(s)</p>
            <p class="mt-1 text-slate-500">{{ $driver->vehicles->pluck('name')->join(', ') }}</p>
        </div>
        @endif

        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/50">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $driver->is_active)) class="rounded border-slate-300 text-primary-600">
            <span class="text-sm font-medium">Active</span>
        </label>

        <div class="flex gap-3">
            <x-ui.button type="submit">Save changes</x-ui.button>
            <x-ui.button href="{{ route('admin.drivers.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
