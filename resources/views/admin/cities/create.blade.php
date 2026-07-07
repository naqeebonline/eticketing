@extends('layouts.admin')
@section('title', 'Add City')
@section('header', 'Add City')
@section('breadcrumb', 'Platform · new city')

@section('content')
<div class="admin-form-shell max-w-lg">
    <x-ui.page-header title="Add city" subtitle="Visible to Bus Stand Admins and passengers when active" />

    <form method="POST" action="{{ route('admin.cities.store') }}" class="admin-form-section space-y-5">
        @csrf
        <x-ui.input label="City name" name="name" :value="old('name')" required placeholder="e.g. Karachi" />
        <x-ui.input label="Sort order" name="sort_order" type="number" min="0" :value="old('sort_order', 0)" hint="Lower numbers appear first in lists" />
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/50">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-primary-600">
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active (show in booking & admin forms)</span>
        </label>
        <div class="flex gap-3 pt-2">
            <x-ui.button type="submit">Save city</x-ui.button>
            <x-ui.button href="{{ route('admin.cities.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
