@extends('layouts.guest')

@section('title', 'Masuk Admin')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="text-lg font-semibold text-telu-ink">Masuk sebagai Admin</h1>
    </div>

    @if (session('status'))
        <p class="mb-4 rounded-lg bg-rg-dsis/10 px-3.5 py-2.5 text-sm text-rg-dsis">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Email</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-telu-muted">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </span>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="admin@email.com"
                    class="w-full rounded-xl border border-telu-border bg-telu-bg-soft/60 py-2.5 pl-10 pr-3.5 text-sm text-telu-ink placeholder:text-telu-muted/60 transition-all focus:border-telu-red focus:bg-white focus:outline-none focus:ring-2 focus:ring-telu-red/20"
                >
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-telu-red">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Kata Sandi</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-telu-muted">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </span>
                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-telu-border bg-telu-bg-soft/60 py-2.5 pl-10 pr-10 text-sm text-telu-ink placeholder:text-telu-muted/60 transition-all focus:border-telu-red focus:bg-white focus:outline-none focus:ring-2 focus:ring-telu-red/20"
                >
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-telu-muted hover:text-telu-ink"
                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                >
                    <svg x-show="!showPassword" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showPassword" x-cloak class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 012.132-3.411m3.087-2.554A9.966 9.966 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" /></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-telu-red">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-telu-body">
            <input type="checkbox" name="remember" class="rounded border-telu-border text-telu-red focus:ring-telu-red">
            Ingat saya
        </label>

        <button type="submit" class="w-full rounded-xl bg-telu-red px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-telu-red/20 transition-all hover:-translate-y-0.5 hover:bg-telu-red-dark hover:shadow-xl hover:shadow-telu-red/30 active:translate-y-0">
            Masuk
        </button>
    </form>

    <a href="{{ route('dashboard') }}" class="mt-6 flex items-center justify-center gap-1.5 text-xs font-medium text-telu-muted hover:text-telu-red">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali ke dashboard publik
    </a>
@endsection
