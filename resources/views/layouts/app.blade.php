<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ ($currentTheme ?? 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Book intercity bus tickets online with e-Ticket. Search routes, choose seats, and get instant QR e-tickets.">
    <link rel="icon" href="{{ asset('logo.jpeg') }}" type="image/jpeg">
    <title>@yield('title', config('app.name', 'e-Ticket'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-shell @yield('body_class')" x-data="{ mobileMenu: false }">
    <x-toast-container />
    <x-ui.dialog />

    <header class="gov-header">
        {{-- Official top ribbon --}}
        <div class="gov-header__ribbon">
            <div class="gov-header__ribbon-inner">
                <div class="gov-header__ribbon-left">
                    <span>Intercity bus e-ticketing</span>
                </div>
                <div class="gov-header__ribbon-right hidden sm:flex">
                    <span>Search · Book · Travel</span>
                    <span class="gov-header__ribbon-dot" aria-hidden="true"></span>
                    <span>Instant QR tickets</span>
                </div>
            </div>
        </div>

        {{-- Main navigation bar --}}
        <div class="gov-header__main">
            <div class="gov-header__main-inner">
                <a href="{{ route('home') }}" class="gov-header__brand group">
                    <div class="gov-header__logo">
                        <img
                            src="{{ asset('logo.jpeg') }}"
                            alt="Tehsil Municipal Administration Timergara"
                            width="144"
                            height="144"
                            decoding="async"
                            fetchpriority="high"
                        >
                    </div>
                    <span class="gov-header__brand-text">
                        <span class="gov-header__title">e-<span class="gov-header__title-accent">Ticket</span></span>
                        <span class="gov-header__tagline">Bus ticketing · Seat booking · E-tickets</span>
                    </span>
                </a>

                <nav class="gov-header__nav hidden lg:flex" aria-label="Main navigation">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'gov-nav-link gov-nav-link--active' : 'gov-nav-link' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <a href="{{ route('home') }}#popular-routes" class="gov-nav-link">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Routes
                    </a>
                    <a href="{{ route('home') }}#how-it-works" class="gov-nav-link">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        How it works
                    </a>
                    @if(request()->routeIs('search'))
                    <span class="gov-nav-link gov-nav-link--active cursor-default">Search results</span>
                    @endif
                    @if(request()->routeIs('book.*'))
                    <span class="gov-nav-link gov-nav-link--active cursor-default">Booking</span>
                    @endif
                    @auth
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="gov-nav-link gov-nav-link--admin">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin panel
                        </a>
                        @endif
                    @endauth
                </nav>

                <div class="gov-header__actions">
                    <button data-theme-toggle type="button" class="gov-header__icon-btn" aria-label="Toggle theme">
                        <svg class="h-5 w-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg>
                        <svg class="h-5 w-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                    </button>
                    @auth
                        <span class="gov-header__user hidden text-sm sm:inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                            @csrf
                            <button type="submit" class="gov-header__btn-ghost">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="gov-header__btn-ghost hidden sm:inline-flex">Sign in</a>
                        <a href="{{ route('register') }}" class="gov-header__btn-primary">
                            <span class="hidden sm:inline">Register</span>
                            <span class="sm:hidden">Join</span>
                        </a>
                    @endauth
                    <button @click="mobileMenu = !mobileMenu" class="gov-header__icon-btn lg:hidden" aria-label="Menu">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="gov-header__goldline" aria-hidden="true"></div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-transition.opacity class="gov-header__mobile lg:hidden" x-cloak>
            <a href="{{ route('home') }}" class="gov-header__mobile-link">Home</a>
            <a href="{{ route('home') }}#popular-routes" class="gov-header__mobile-link">Popular routes</a>
            <a href="{{ route('home') }}#how-it-works" class="gov-header__mobile-link">How it works</a>
            @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="gov-header__mobile-link">Admin panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 pt-3">
                    @csrf
                    <button type="submit" class="gov-header__mobile-link w-full text-left text-red-200">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="gov-header__mobile-link">Sign in</a>
                <a href="{{ route('register') }}" class="gov-header__mobile-cta">Create account</a>
            @endauth
        </div>
    </header>

    <main class="flex-1">
        @hasSection('hero')
            @yield('hero')
        @endif
        <div @class([\Illuminate\Support\Facades\View::hasSection('hero') ? '' : 'page-container'])>
            @hasSection('hero')
                <div class="public-section py-4">
                    <x-flash-messages />
                </div>
            @else
                <x-flash-messages />
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="gov-footer">
        <div class="gov-footer__mesh" aria-hidden="true"></div>

        <div class="gov-footer__main">
            <div class="gov-footer__grid">
                {{-- Brand column --}}
                <div class="gov-footer__brand-col">
                    <a href="{{ route('home') }}" class="gov-footer__brand">
                        <div class="gov-footer__logo">
                            <img
                                src="{{ asset('logo.jpeg') }}"
                                alt="Tehsil Municipal Administration Timergara"
                                width="120"
                                height="120"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <span>
                            <span class="gov-footer__brand-title">e-<span class="text-pak-gold">Ticket</span></span>
                            <span class="gov-footer__brand-sub">Bus e-ticketing platform</span>
                        </span>
                    </a>
                    <p class="gov-footer__desc">
                        Search intercity routes, select seats on a live map, pay securely, and receive verified QR digital tickets instantly.
                    </p>
                    <div class="gov-footer__helpline">
                        <div class="gov-footer__helpline-icon" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="gov-footer__helpline-label">Customer support</p>
                            <p class="gov-footer__helpline-number">+92 300 1234567</p>
                            <p class="gov-footer__helpline-hours">24/7 · Always available</p>
                        </div>
                    </div>
                </div>

                {{-- Quick links --}}
                <div>
                    <h3 class="gov-footer__heading">Quick links</h3>
                    <ul class="gov-footer__links">
                        <li><a href="{{ route('home') }}">Book bus ticket</a></li>
                        <li><a href="{{ route('home') }}#popular-routes">Popular routes</a></li>
                        <li><a href="{{ route('home') }}#how-it-works">How to book</a></li>
                        <li><a href="{{ route('register') }}">Create account</a></li>
                        <li><a href="{{ route('login') }}">Sign in</a></li>
                    </ul>
                </div>

                {{-- Services --}}
                <div>
                    <h3 class="gov-footer__heading">Services</h3>
                    <ul class="gov-footer__links">
                        <li><a href="{{ route('home') }}">Route search</a></li>
                        <li><a href="{{ route('home') }}">Seat selection</a></li>
                        <li><a href="{{ route('home') }}">QR e-ticket</a></li>
                        <li><span class="text-white/40">Ticket verification</span></li>
                        <li><span class="text-white/40">Refund policy</span></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="gov-footer__heading">Contact</h3>
                    <ul class="gov-footer__contact">
                        <li>
                            <svg class="h-4 w-4 shrink-0 text-pak-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            help@eticket.com
                        </li>
                        <li>
                            <svg class="h-4 w-4 shrink-0 text-pak-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            Online · 24/7
                        </li>
                        <li>
                            <svg class="h-4 w-4 shrink-0 text-pak-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mon – Sun, 24 hours
                        </li>
                    </ul>

                    <div class="gov-footer__badges">
                        @foreach(['SSL Secured', 'QR Verified', 'Instant booking'] as $badge)
                        <span class="gov-footer__badge">{{ $badge }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="gov-footer__bar">
            <div class="gov-footer__bar-inner">
                <p>&copy; {{ date('Y') }} e-Ticket. All rights reserved.</p>
                <div class="gov-footer__bar-links">
                    <span>Privacy policy</span>
                    <span class="text-white/20">|</span>
                    <span>Terms of use</span>
                    <span class="text-white/20">|</span>
                    <span>Accessibility</span>
                </div>
            </div>
        </div>
    </footer>

    <style>[x-cloak]{display:none!important}</style>
    @stack('scripts')
</body>
</html>
