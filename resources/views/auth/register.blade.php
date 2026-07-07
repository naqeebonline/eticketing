@extends('layouts.app')
@section('title', 'Create account')

@section('content')
<div class="booking-flow-shell mx-auto flex min-h-[70vh] max-w-md flex-col justify-center py-12">
    <div class="text-center">
        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 text-white shadow-lg shadow-primary-500/25">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        </span>
        <h1 class="font-display mt-4 text-2xl font-bold text-slate-900 dark:text-white">Create your account</h1>
        <p class="mt-2 text-sm text-slate-500">Book buses faster with a free passenger account</p>
    </div>

    <div class="auth-panel mt-8">
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <x-ui.input label="Full name" name="name" :value="old('name')" required />
            <x-ui.input label="Email" name="email" type="email" :value="old('email')" required />
            <x-ui.input label="Phone" name="phone" type="tel" :value="old('phone')" hint="Optional — for booking updates" />
            <x-ui.input label="Password" name="password" type="password" required />
            <x-ui.input label="Confirm password" name="password_confirmation" type="password" required />
            <x-ui.button type="submit" class="w-full btn-lg shadow-lg shadow-primary-500/25">Create account</x-ui.button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account? <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Sign in</a>
        </p>
    </div>
</div>
@endsection
