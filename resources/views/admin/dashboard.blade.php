@extends('layouts.app')

@section('title', 'Panel Admin')

@section('content')
    <x-page-header
        title="Panel Admin"
        subtitle="Masuk sebagai {{ auth()->user()->name }} ({{ auth()->user()->email }})"
    />

    <div class="card-premium p-6">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Koreksi Data Dosen</h2>
        <p class="mt-2 text-sm text-telu-body">
            Kalau ada laporan data dosen yang salah (identitas, klasifikasi, atau metrik), cari dosennya dan koreksi lewat form edit.
        </p>

        <a href="{{ route('admin.lecturers.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-md bg-telu-red px-4 py-2 text-sm font-medium text-white hover:bg-telu-red-dark">
            Cari &amp; Koreksi Data Dosen
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-6 border-t border-telu-border pt-6">
            @csrf
            <button type="submit" class="rounded-md border border-telu-border px-4 py-2 text-sm font-medium text-telu-body hover:border-telu-red/50 hover:text-telu-red">
                Keluar
            </button>
        </form>
    </div>
@endsection
