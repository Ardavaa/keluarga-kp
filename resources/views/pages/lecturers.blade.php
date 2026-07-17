@extends('layouts.app')

@section('title', 'Profil Dosen')

@php
    $hasActiveFilter = $search !== '' || $prodi !== '' || $kelompok !== '' || $bidang !== '' || $tahun !== '';
@endphp

@section('content')
    <x-page-header
        title="Profil Dosen"
        subtitle="Cari dan saring dosen FIF berdasarkan prodi, bidang keahlian, kelompok keahlian, atau tahun publikasi"
    />

    <form method="GET" action="{{ route('lecturers.index') }}" class="card-premium bg-white p-6 shadow-sm">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-4">
                <label for="search" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-telu-muted">Cari</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Nama, bidang keahlian, atau prodi..."
                    class="w-full rounded-xl border border-telu-border/60 px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                >
            </div>

            <div class="md:col-span-2">
                <label for="prodi" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-telu-muted">Prodi</label>
                <select id="prodi" name="prodi" class="w-full rounded-xl border border-telu-border/60 px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red">
                    <option value="">Semua Prodi</option>
                    @foreach ($filterOptions['prodi'] as $option)
                        <option value="{{ $option }}" @selected($prodi === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="kelompok" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-telu-muted">Kelompok Keahlian</label>
                <select id="kelompok" name="kelompok" class="w-full rounded-xl border border-telu-border/60 px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red">
                    <option value="">Semua KK</option>
                    @foreach ($filterOptions['kelompok'] as $option)
                        <option value="{{ $option }}" @selected($kelompok === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="bidang" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-telu-muted">Bidang Keahlian</label>
                <select id="bidang" name="bidang" class="w-full rounded-xl border border-telu-border/60 px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red">
                    <option value="">Semua Bidang</option>
                    @foreach ($filterOptions['bidang'] as $option)
                        <option value="{{ $option }}" @selected($bidang === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="tahun" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-telu-muted">Tahun Publikasi</label>
                <select id="tahun" name="tahun" class="w-full rounded-xl border border-telu-border/60 px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red">
                    <option value="">Semua Tahun</option>
                    @foreach ($filterOptions['tahun'] as $option)
                        <option value="{{ $option }}" @selected($tahun == $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-telu-border/10 pt-4">
            <div class="flex items-center gap-2">
                <label for="sort" class="text-xs font-bold uppercase tracking-wider text-telu-muted">Urutkan</label>
                <select id="sort" name="sort" class="rounded-xl border border-telu-border/60 px-3 py-2 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red">
                    <option value="name" @selected($sort === 'name')>Nama</option>
                    <option value="research_group" @selected($sort === 'research_group')>Kelompok Keahlian</option>
                    <option value="study_program" @selected($sort === 'study_program')>Prodi</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                @if ($hasActiveFilter)
                    <a href="{{ route('lecturers.index') }}" class="text-sm font-semibold text-telu-muted hover:text-telu-red">
                        Reset Filter
                    </a>
                @endif

                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-telu-red px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-telu-red-dark">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    Terapkan
                </button>
            </div>
        </div>
    </form>

    <div class="mb-6 mt-6 flex items-center justify-between">
        <p class="text-sm font-semibold text-telu-body">
            Menampilkan <span class="font-extrabold text-telu-ink">{{ $lecturers->count() }}</span> dosen
        </p>

        <x-export-buttons excel-route="lecturers.export.excel" pdf-route="lecturers.export.pdf" :query="request()->query()" />
    </div>

    @if ($lecturers->isEmpty())
        <div class="rounded-2xl border border-telu-border/30 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-telu-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
            <p class="mt-4 text-base font-semibold text-telu-ink">Tidak Ditemukan</p>
            <p class="mt-1 text-sm text-telu-muted">Tidak ada dosen yang cocok dengan kombinasi filter ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($lecturers as $lecturer)
                <x-lecturer-card :lecturer="$lecturer" />
            @endforeach
        </div>
    @endif
@endsection
