@extends('layouts.app')
@section('title', 'Your Ticket')
@section('body_class', 'thermal-print-page')

@section('content')
<div class="booking-flow-shell mx-auto max-w-lg">
    <x-public.booking-steps :current="4" />

    @php
        $isHeld = $booking->status === \App\Enums\BookingStatus::Held;
    @endphp

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm font-medium text-success-800 print:hidden dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 text-center print:hidden">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full {{ $isHeld ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10' : 'bg-success-50 text-success-600 dark:bg-success-500/10' }}">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        @if($isHeld)
        <h1 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Booking held</h1>
        <p class="mt-1 text-slate-500">Seats reserve ho gaye hain. Payment counter par karein aur boarding par QR dikhayen.</p>
        @else
        <h1 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Booking confirmed!</h1>
        <p class="mt-1 text-slate-500">Your receipt is ready. Show the QR code at boarding.</p>
        @endif
    </div>

    <div class="thermal-receipt-shell">
        <x-booking.receipt-card :booking="$booking" />
    </div>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row print:hidden">
        <button onclick="window.print()" class="btn-primary flex-1 btn-lg">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print receipt
        </button>
        <a href="{{ route('home') }}" class="btn-secondary flex-1 btn-lg text-center">Book another trip</a>
    </div>
</div>
@endsection
