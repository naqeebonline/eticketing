@extends('layouts.app')
@section('title', 'Payment')

@section('content')
<div class="booking-flow-shell mx-auto max-w-lg">
    <x-public.booking-steps :current="4" />

    <div class="booking-page-header text-center">
        <h1 class="booking-page-title">Complete payment</h1>
        <p class="mt-2 text-slate-500">Booking <span class="font-mono font-semibold">{{ $booking->booking_number }}</span></p>
    </div>

    <div class="ticket-card mb-6 overflow-hidden">
        <div class="ticket-card-header">
            <p class="text-sm font-medium text-primary-100">Total amount</p>
            <p class="font-display mt-1 text-4xl font-extrabold tracking-tight">PKR {{ number_format($booking->total_amount) }}</p>
        </div>
        <div class="grid grid-cols-2 gap-px bg-slate-200 text-sm dark:bg-slate-700">
            <div class="bg-white p-4 dark:bg-slate-900">
                <p class="text-xs uppercase text-slate-400">Route</p>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $booking->schedule->route->departure_city }} → {{ $booking->schedule->route->destination_city }}</p>
            </div>
            <div class="bg-white p-4 dark:bg-slate-900">
                <p class="text-xs uppercase text-slate-400">Date</p>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $booking->schedule->departure_date->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('book.payment.process', $booking->uuid) }}" class="search-card space-y-3 p-5">
        @csrf
        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Payment method</p>
        @foreach([
            'cash' => ['Cash', 'Pay at counter or to conductor', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            'jazzcash' => ['JazzCash', 'Mobile wallet — instant confirmation', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            'easypaisa' => ['Easypaisa', 'Mobile wallet — instant confirmation', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            'stripe' => ['Card', 'Visa, Mastercard via Stripe', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ] as $value => [$label, $desc, $icon])
        <label class="payment-method-card">
            <input type="radio" name="method" value="{{ $value }}" {{ $loop->first ? 'checked' : '' }} class="text-primary-600 focus:ring-primary-500">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $icon }}"/></svg>
            </span>
            <span class="text-left">
                <span class="block font-semibold text-slate-900 dark:text-white">{{ $label }}</span>
                <span class="block text-sm text-slate-500">{{ $desc }}</span>
            </span>
        </label>
        @endforeach
        <x-ui.button type="submit" class="mt-4 w-full btn-lg shadow-lg shadow-primary-500/25">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Pay securely
        </x-ui.button>
    </form>
</div>
@endsection
