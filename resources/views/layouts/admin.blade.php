<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ ($currentTheme ?? 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — BSS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $isStandAdmin = auth()->user()->isBusStandAdmin();
    $adminVariant = $isStandAdmin ? 'admin-stand' : 'admin-platform';
@endphp
<body class="admin-shell {{ $adminVariant }} min-h-screen @yield('body_class')" x-data="{ sidebarOpen: false }">
    <x-toast-container />
    <x-ui.dialog />

    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/70 backdrop-blur-sm lg:hidden"
        x-cloak
    ></div>

    <x-admin.sidebar />

    <div class="admin-main flex min-h-screen min-w-0 flex-col lg:ml-[17.5rem]">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-900/90 sm:px-6">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800"
                        aria-label="Open menu"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="font-display text-lg font-bold text-slate-900 dark:text-white">@yield('header', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                        <p class="text-xs text-slate-500">@yield('breadcrumb')</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <button data-theme-toggle type="button" class="btn-ghost btn-sm !rounded-xl !p-2" aria-label="Toggle theme">
                        <svg class="h-5 w-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg class="h-5 w-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                    </button>
                    <div class="hidden h-8 w-px bg-slate-200 sm:block dark:bg-slate-700"></div>
                    <div class="flex items-center gap-2.5 rounded-xl border border-slate-200/80 bg-slate-50/80 py-1 pl-1 pr-3 dark:border-slate-700 dark:bg-slate-800/50">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 text-xs font-bold text-white shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold leading-tight text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ auth()->user()->roleLabel() }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-ghost btn-sm rounded-xl text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-500/10">Logout</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <x-flash-messages />
                @yield('content')
            </main>
    </div>

    <style>[x-cloak]{display:none!important}</style>
    @stack('scripts')
</body>
</html>
