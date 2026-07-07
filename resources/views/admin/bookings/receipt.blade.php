@extends('layouts.admin')
@section('title', 'Booking Receipt')
@section('header', 'Booking Receipt')
@section('breadcrumb', $booking->booking_number)

@section('body_class', 'thermal-print-page')

@section('content')
<div class="mx-auto max-w-lg">
    <a href="{{ route('admin.bookings.show', $booking->uuid) }}" class="admin-row-action mb-6 inline-flex print:hidden">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to booking
    </a>

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 print:hidden dark:border-emerald-800/50 dark:bg-emerald-950/30 dark:text-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 print:hidden">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Customer receipt</h2>
        <p class="mt-1 text-sm text-slate-500">Thermal printer ke liye Print dabayein — sirf receipt print hogi.</p>
    </div>

    <div class="thermal-receipt-shell">
        <x-booking.receipt-card :booking="$booking" />
    </div>

    <div class="mt-6 flex flex-wrap gap-3 print:hidden">
        <button type="button" onclick="window.print()" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print receipt
        </button>
        <x-ui.button href="{{ route('admin.bookings.index') }}" variant="secondary">All bookings</x-ui.button>
        <x-ui.button href="{{ route('admin.bookings.create') }}" variant="secondary">New booking</x-ui.button>
    </div>
</div>
@endsection
