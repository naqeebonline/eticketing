@extends('layouts.app')
@section('title', 'Sign in')

@section('content')
<div class="booking-flow-shell mx-auto flex min-h-[70vh] max-w-5xl items-center py-12">
    <div class="grid w-full gap-10 lg:grid-cols-2 lg:gap-16">
        <div class="hidden flex-col justify-center lg:flex">
            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">Welcome back</p>
            <h1 class="font-display mt-3 text-3xl font-bold text-slate-900 dark:text-white">Sign in to manage your trips</h1>
            <p class="mt-4 text-slate-500 leading-relaxed">Access your bookings, e-tickets, and travel history from one place.</p>
            <ul class="mt-8 space-y-3 text-sm text-slate-600 dark:text-slate-400">
                @foreach(['View and print e-tickets', 'Faster checkout on repeat bookings', 'Secure account with role-based access'] as $item)
                <li class="flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        <div>
            <div class="mb-8 text-center lg:text-left">
                <h2 class="font-display text-2xl font-bold text-slate-900 dark:text-white lg:hidden">Sign in</h2>
                <p class="mt-2 text-sm text-slate-500">Enter your credentials to continue</p>
            </div>

            <div class="auth-panel">
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <x-ui.input label="Email" name="email" type="email" :value="old('email')" required autocomplete="email" :error="$errors->first('email')" />
                    <x-ui.input label="Password" name="password" type="password" required autocomplete="current-password" />
                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        Remember me
                    </label>
                    <x-ui.button type="submit" class="w-full btn-lg shadow-lg shadow-primary-500/25">Sign in</x-ui.button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-500">
                    No account? <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Create one free</a>
                </p>
                @if(app()->environment('local'))
                <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-400">
                    <p class="font-semibold text-slate-700 dark:text-slate-300">Demo accounts</p>
                    <ul class="mt-2 space-y-1">
                        <li><strong>Super Admin</strong> — admin@bssbooking.com</li>
                        <li><strong>Terminal / Adda Admin</strong> — terminal@bssbooking.com</li>
                        <li><strong>Bus Stand Admin</strong> — stand@bssbooking.com</li>
                        <li><strong>Passenger</strong> — passenger@bssbooking.com</li>
                        <li class="text-slate-400">Password: password (all)</li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
