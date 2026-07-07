@extends('layouts.admin')
@section('title', 'Schedules')
@section('header', 'Schedules')
@section('breadcrumb', 'Weekly timetables')

@section('content')
<x-ui.page-header title="Weekly schedules" subtitle="Har vehicle ki permanent weekly timetable — har hafte automatically repeat hoti hai">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.schedules.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add weekly schedule
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Vehicle</th>
                    <th>Weekly times</th>
                    <th>Fare</th>
                    <th>Days/week</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td>
                        <span class="admin-route-pill">
                            {{ $plan->route->departure_city }}
                            <span class="admin-route-arrow">→</span>
                            {{ $plan->route->destination_city }}
                        </span>
                    </td>
                    <td>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ $plan->vehicle->name }}</p>
                        @if($plan->vehicle->bus_number)
                        <p class="text-xs text-slate-500">{{ $plan->vehicle->bus_number }}</p>
                        @endif
                    </td>
                    <td>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($plan->days as $day)
                            <span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                {{ substr($day->weekdayLabel(), 0, 3) }}
                                {{ \Carbon\Carbon::parse($day->departure_time)->format('h:i A') }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="font-semibold">PKR {{ number_format($plan->fare) }}</td>
                    <td>
                        <span class="rounded-lg bg-primary-50 px-2 py-1 text-xs font-bold text-primary-700 dark:bg-primary-500/10 dark:text-primary-400">
                            {{ $plan->days->count() }} days
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.schedules.plan.edit', $plan) }}" class="admin-row-action">Edit timetable</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">
                    <x-ui.empty-state title="No weekly schedules" description="Ek baar vehicle ki weekly timetable set karein — har hafte khud repeat hogi.">
                        <x-slot:action><x-ui.button href="{{ route('admin.schedules.create') }}">Add weekly schedule</x-ui.button></x-slot:action>
                    </x-ui.empty-state>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($plans->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $plans->links() }}</div>
    @endif
</div>
@endsection
