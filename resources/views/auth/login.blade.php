@extends('layouts.guest')

@section('title', 'Masuk Admin')

@section('content')
    <h1 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Masuk sebagai Admin</h1>
    <p class="mt-1 text-xs text-telu-muted">Khusus admin — untuk mengoreksi data dosen yang dilaporkan salah.</p>

    @if (session('status'))
        <p class="mt-4 text-sm text-rg-dsis">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-telu-red">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Kata Sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
            >
        </div>

        <label class="flex items-center gap-2 text-sm text-telu-body">
            <input type="checkbox" name="remember" class="rounded border-telu-border text-telu-red focus:ring-telu-red">
            Ingat saya
        </label>

        <button type="submit" class="w-full rounded-md bg-telu-red px-4 py-2.5 text-sm font-medium text-white hover:bg-telu-red-dark">
            Masuk
        </button>
    </form>

    <a href="{{ route('dashboard') }}" class="mt-6 block text-center text-xs text-telu-muted hover:text-telu-red">
        &larr; Kembali ke dashboard publik
    </a>
@endsection
