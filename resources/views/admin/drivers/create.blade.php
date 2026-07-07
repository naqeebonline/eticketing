@extends('layouts.admin')
@section('title', 'Add Driver')
@section('header', 'Add Driver')
@section('breadcrumb', 'New driver')

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        title="Add driver"
        subtitle="Driver ko stand par register karein — baad mein vehicle ya schedule par assign karein"
    />

    <form method="POST" action="{{ route('admin.drivers.store') }}" class="space-y-6">
        @csrf

        <x-ui.form-section title="Stand" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5">
            <x-ui.select label="Bus stand" name="bus_stand_id" required>
                <option value="">— Select stand —</option>
                @foreach($busStands as $stand)
                <option value="{{ $stand->id }}" @selected(old('bus_stand_id') == $stand->id)>
                    {{ $stand->displayTitle() }}
                    @if($stand->terminal) — {{ $stand->terminal->name }}@endif
                </option>
                @endforeach
            </x-ui.select>
        </x-ui.form-section>

        <x-ui.form-section title="Personal details" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
            <x-ui.input label="Full name" name="name" required :value="old('name')" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone')" />
                <x-ui.input label="CNIC" name="cnic" :value="old('cnic')" placeholder="Optional" />
            </div>
            <x-ui.textarea label="Address" name="address" rows="2">{{ old('address') }}</x-ui.textarea>
            <x-ui.input label="Emergency contact" name="emergency_contact" type="tel" :value="old('emergency_contact')" />
        </x-ui.form-section>

        <x-ui.form-section title="License" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            <x-ui.input label="License number" name="license_number" :value="old('license_number')" placeholder="Auto-generated if empty" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.input label="License expiry" name="license_expiry" type="date" required :value="old('license_expiry')" min="{{ today()->format('Y-m-d') }}" />
                <x-ui.input label="License class" name="license_class" :value="old('license_class')" placeholder="e.g. LTV" />
            </div>
        </x-ui.form-section>

        <div class="flex gap-3">
            <x-ui.button type="submit">Save driver</x-ui.button>
            <x-ui.button href="{{ route('admin.drivers.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
