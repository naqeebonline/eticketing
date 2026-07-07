@extends('layouts.admin')
@section('title', 'Edit City')
@section('header', 'Edit City')
@section('breadcrumb', $city->name)

@section('content')
<div class="admin-form-shell max-w-lg">
    <x-ui.page-header title="Edit city" :subtitle="$city->name" />

    <form method="POST" action="{{ route('admin.cities.update', $city) }}" class="admin-form-section space-y-5">
        @csrf
        @method('PUT')
        <x-ui.input label="City name" name="name" :value="old('name', $city->name)" required />
        <x-ui.input label="Sort order" name="sort_order" type="number" min="0" :value="old('sort_order', $city->sort_order)" />
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/50">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $city->is_active)) class="rounded border-slate-300 text-primary-600">
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
        </label>
        <div class="flex gap-3 pt-2">
            <x-ui.button type="submit">Update city</x-ui.button>
            <x-ui.button href="{{ route('admin.cities.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
