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

    <form method="GET" action="{{ route('lecturers.index') }}" class="card-premium p-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-4">
                <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Cari</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Nama, bidang keahlian, atau prodi..."
                    class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                >
            </div>

            <div class="md:col-span-2">
                <label for="prodi" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Prodi</label>
                <x-filter-select id="prodi" name="prodi" :options="$filterOptions['prodi']" :selected="$prodi" placeholder="Semua Prodi" />
            </div>

            <div class="md:col-span-2">
                <label for="kelompok" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Kelompok Keahlian</label>
                <x-filter-select id="kelompok" name="kelompok" :options="$filterOptions['kelompok']" :selected="$kelompok" placeholder="Semua KK" />
            </div>

            <div class="md:col-span-2">
                <label for="bidang" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Bidang Keahlian</label>
                <x-filter-select id="bidang" name="bidang" :options="$filterOptions['bidang']" :selected="$bidang" placeholder="Semua Bidang" />
            </div>

            <div class="md:col-span-2">
                <label for="tahun" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Tahun Publikasi</label>
                <x-filter-select id="tahun" name="tahun" :options="$filterOptions['tahun']" :selected="$tahun" placeholder="Semua Tahun" />
            </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-telu-border pt-4">
            <div class="flex items-center gap-2">
                <label for="sort" class="text-xs font-semibold uppercase tracking-wide text-telu-muted">Urutkan</label>
                <div class="w-48">
                    <x-filter-select id="sort" name="sort" :options="['full_name' => 'Nama', 'research_group' => 'Kelompok Keahlian', 'study_program' => 'Prodi']" :selected="$sort" />
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if ($hasActiveFilter)
                    <a href="{{ route('lecturers.index') }}" class="text-sm text-telu-muted hover:text-telu-red">
                        Reset Filter
                    </a>
                @endif

                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-telu-red px-5 py-2.5 text-sm font-medium text-white hover:bg-telu-red-dark">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    Terapkan
                </button>
            </div>
        </div>
    </form>

    <div class="mb-6 mt-6 flex items-center justify-between">
        <p class="text-sm text-telu-body">
            Menampilkan <span class="font-semibold text-telu-ink">{{ $lecturers->count() }}</span> dosen
        </p>

        <x-export-buttons excel-route="lecturers.export.excel" pdf-route="lecturers.export.pdf" :query="request()->query()" />
    </div>

    @if ($lecturers->isEmpty())
        <x-empty-state icon="search" title="Tidak Ditemukan" message="Tidak ada dosen yang cocok dengan kombinasi filter ini." />
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($lecturers as $lecturer)
                <x-lecturer-card :lecturer="$lecturer" />
            @endforeach
        </div>
    @endif
@endsection
