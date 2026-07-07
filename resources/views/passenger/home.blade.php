@extends('layouts.app')
@section('title', 'Book Bus Tickets Online — e-Ticket')
@section('body_class', 'landing-page')

@section('hero')
<section class="landing-hero">
    <div class="landing-hero__mesh" aria-hidden="true"></div>
    <div class="landing-hero__grid-bg" aria-hidden="true"></div>
    <div class="landing-hero__road" aria-hidden="true"></div>

    <div class="public-section landing-hero__inner">
        <div class="landing-hero__layout">
            {{-- Copy --}}
            <div class="animate-slide-up">
                <div class="landing-hero__badge">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-pak-gold opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-pak-gold"></span>
                    </span>
                    Smart bus e-ticketing
                </div>

                <h1 class="landing-hero__title">
                    Book your bus ticket with
                    <span class="bg-gradient-to-r from-pak-gold-light via-white to-pak-gold bg-clip-text text-transparent">e-Ticket</span>
                </h1>

                <p class="landing-hero__subtitle">
                    Search intercity routes, choose your seat on a live map, pay securely, and download your digital ticket with QR — all in under 3 minutes.
                </p>

                <div class="landing-hero__pills">
                    @foreach(['Instant QR e-ticket', 'Live seat selection', 'Secure payments'] as $pill)
                    <span class="landing-hero__pill">
                        <svg class="h-3.5 w-3.5 text-pak-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        {{ $pill }}
                    </span>
                    @endforeach
                </div>

                @if($popularRoutes->isNotEmpty())
                <div class="landing-quick-routes">
                    <span class="w-full text-xs font-semibold uppercase tracking-wider text-slate-500 sm:w-auto sm:py-1.5">Trending:</span>
                    @foreach($popularRoutes->take(4) as $route)
                    <a href="{{ route('search', ['from' => $route->departure_city, 'to' => $route->destination_city, 'date' => today()->format('Y-m-d')]) }}" class="landing-quick-route">
                        {{ $route->departure_city }}
                        <svg class="h-3 w-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        {{ $route->destination_city }}
                    </a>
                    @endforeach
                </div>
                @endif

                <div class="landing-hero__stats">
                    @foreach([
                        ['50+', 'Bus operators'],
                        ['200+', 'Daily routes'],
                        ['10k+', 'Tickets booked'],
                    ] as [$stat, $label])
                    <div>
                        <p class="landing-hero__stat-value">{{ $stat }}</p>
                        <p class="landing-hero__stat-label">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Booking form --}}
            <div class="landing-hero__form-col animate-slide-up" style="animation-delay: 0.1s">
                <x-public.search-form variant="hero" />
                <p class="mt-4 text-center text-xs text-slate-400">
                    No account needed · Guest booking supported
                </p>
            </div>

            {{-- Decorative e-ticket preview --}}
            <div class="landing-hero__ticket-col animate-slide-up" style="animation-delay: 0.2s">
                <div class="landing-ticket-mock">
                    <p class="landing-ticket-mock__brand">e-<span class="text-primary-600">Ticket</span></p>
                    <p class="landing-ticket-mock__type">Bus E-Ticket</p>
                    <p class="landing-ticket-mock__route">Karachi → Lahore</p>
                    <div class="landing-ticket-mock__meta">
                        <p>{{ today()->format('D, M d, Y') }} · 09:00 AM</p>
                        <p>Seat A12 · AC Luxury</p>
                        <p>PNR: ET-{{ today()->format('ymd') }}-4821</p>
                    </div>
                    <div class="landing-ticket-mock__qr" aria-hidden="true">
                        <svg class="h-12 w-12 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm11-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v5h-5v-2h3v-3z"/></svg>
                    </div>
                    <span class="landing-ticket-mock__status">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Confirmed
                    </span>
                </div>
                <p class="mt-6 text-center text-xs text-slate-500">Your ticket — ready on screen</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
{{-- Trust strip --}}
<div class="landing-trust-strip">
    @foreach([
        ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'Verified operators'],
        ['M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'Secure checkout'],
        ['M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'JazzCash · Easypaisa · Card'],
        ['M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'Book from any device'],
    ] as [$icon, $label])
    <span class="landing-trust-item">
        <svg class="h-5 w-5 text-pak-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
        {{ $label }}
    </span>
    @endforeach
</div>

{{-- Popular routes --}}
@if($popularRoutes->isNotEmpty())
<section id="popular-routes" class="public-section py-16 sm:py-20">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="landing-section-title">Popular bus routes</h2>
            <p class="landing-section-sub">Top destinations — one click to search</p>
        </div>
    </div>

    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($popularRoutes as $route)
        <a href="{{ route('search', ['from' => $route->departure_city, 'to' => $route->destination_city, 'date' => today()->format('Y-m-d')]) }}" class="landing-route-card group">
            <div class="landing-route-card__bar"></div>
            <div class="landing-route-card__body">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="landing-route-card__cities">{{ $route->departure_city }}</span>
                            <svg class="h-4 w-4 shrink-0 text-pak-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            <span class="landing-route-card__cities">{{ $route->destination_city }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ $route->name }}</p>
                        @if($route->busStand)
                        <p class="mt-1 text-xs text-slate-400">{{ $route->busStand->name }}</p>
                        @endif
                    </div>
                    <div class="landing-route-card__fare shrink-0">
                        <p class="text-[10px] font-medium uppercase opacity-90">from</p>
                        <p class="font-display text-sm font-bold">PKR {{ number_format($route->base_fare) }}</p>
                    </div>
                </div>
                <p class="mt-4 text-xs font-semibold text-pak-green opacity-0 transition group-hover:opacity-100">Search buses →</p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- How it works --}}
<section id="how-it-works" class="border-y border-slate-200/80 bg-slate-50 py-16 sm:py-20 dark:border-slate-800 dark:bg-slate-900/40">
    <div class="public-section">
        <div class="text-center">
            <h2 class="landing-section-title">How e-Ticket works</h2>
            <p class="landing-section-sub mx-auto max-w-xl">From search to boarding pass — four simple steps</p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['01', 'Search route', 'Pick departure, destination & date to see all available buses.', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                ['02', 'Select seat', 'Interactive seat map — window, aisle, normal or luxury.', 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z'],
                ['03', 'Pay securely', 'Cash, JazzCash, Easypaisa or card — your choice.', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                ['04', 'Get e-ticket', 'Instant QR ticket on screen — show at boarding.', 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
            ] as [$step, $title, $desc, $icon])
            <div class="landing-step-card">
                <span class="landing-step-card__num">{{ $step }}</span>
                <div class="landing-step-card__icon">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-display mt-4 text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Features --}}
<section class="public-section py-16 sm:py-20">
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl bg-gradient-to-br from-pak-green via-pak-green-mid to-pak-green-light p-8 text-white shadow-xl shadow-pak-green/20 sm:p-10">
            <p class="text-xs font-bold uppercase tracking-widest text-pak-gold-light">Why e-Ticket</p>
            <h2 class="font-display mt-2 text-2xl font-bold sm:text-3xl">Travel smarter, not harder</h2>
            <p class="mt-3 leading-relaxed text-white/80">Skip the counter queues. Book from home, office, or on the road — your bus e-ticket is always in your pocket.</p>
            <ul class="mt-8 space-y-4">
                @foreach(['QR-verified digital tickets', 'AC, business & luxury coaches', 'Real-time seat availability', 'Book 24/7 from any device'] as $item)
                <li class="flex items-center gap-3 text-sm">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/20">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
            @guest
            <a href="{{ route('register') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-pak-gold px-5 py-2.5 text-sm font-semibold text-pak-green-dark shadow-lg transition hover:bg-pak-gold-light">
                Create free account
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @endguest
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            @foreach([
                ['Live seat maps', 'See free seats before you pay — no surprises.', 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10'],
                ['Best fares', 'Compare prices across operators instantly.', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Mobile ready', 'Optimized for phones — book on the go.', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['Easy support', 'Help when you need it — before & after travel.', 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
            ] as [$title, $desc, $icon])
            <div class="public-feature-tile">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pak-green-soft text-pak-green dark:bg-pak-green/20 dark:text-pak-green-light">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="mt-3 font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="public-section pb-16 sm:pb-20">
    <div class="landing-cta">
        <div class="landing-cta__glow" aria-hidden="true"></div>
        <div class="relative">
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Your next bus is one search away</h2>
            <p class="mx-auto mt-3 max-w-lg text-slate-400">Join thousands of travelers who book smarter with e-Ticket every day.</p>
            <a href="#top" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;" class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-pak-gold px-8 py-4 text-base font-bold text-pak-green-dark shadow-lg shadow-pak-gold/30 transition hover:bg-pak-gold-light">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Book bus ticket now
            </a>
        </div>
    </div>
</section>
@endsection
