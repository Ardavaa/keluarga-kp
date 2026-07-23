@extends('layouts.app')

@section('title', 'Koreksi: ' . $lecturer->full_name)

@php
    $fields = [
        'full_name' => 'Nama',
        'name_with_title' => 'Nama dengan Gelar',
        'lecturer_code' => 'Kode Dosen',
        'study_program' => 'Program Studi',
        'research_group' => 'Kelompok Keahlian',
        'academic_rank' => 'Jabatan Fungsional',
        'field' => 'Bidang Keilmuan',
        'citation_count' => 'Sitasi',
        'h_index' => 'H-Index',
        'i10_index' => 'i10-Index',
    ];
@endphp

@section('content')
    <x-page-header
        title="Koreksi Data Dosen"
        subtitle="{{ $lecturer->full_name }} &middot; Kode: {{ $lecturer->lecturer_code ?: '—' }} &middot; NIP: {{ $lecturer->code }}"
    />

    <a href="{{ route('admin.lecturers.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-telu-muted hover:text-telu-red">
        &larr; Kembali ke daftar dosen
    </a>

    @if (session('status'))
        <div class="mb-4 rounded-md border border-rg-dsis/30 bg-rg-dsis/5 px-4 py-3 text-sm text-telu-ink">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.lecturers.update', $lecturer) }}" class="card-premium p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($fields as $key => $label)
                <div>
                    <label for="{{ $key }}" class="mb-1.5 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-telu-muted">
                        {{ $label }}
                        @if (in_array($key, $overriddenFields, true))
                            <span class="rounded bg-telu-red/10 px-1.5 py-0.5 text-[10px] font-semibold normal-case tracking-normal text-telu-red">
                                Sudah dikoreksi &mdash; dilindungi dari import
                            </span>
                        @endif
                    </label>

                    @if (in_array($key, ['citation_count', 'h_index', 'i10_index'], true))
                        <input
                            type="number"
                            min="0"
                            id="{{ $key }}"
                            name="{{ $key }}"
                            value="{{ old($key, $lecturer->{$key}) }}"
                            class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                        >
                    @else
                        <input
                            type="text"
                            id="{{ $key }}"
                            name="{{ $key }}"
                            value="{{ old($key, $lecturer->{$key}) }}"
                            class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                        >
                    @endif

                    @error($key)
                        <p class="mt-1.5 text-xs text-telu-red">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <hr class="my-6 border-telu-border/60">

        <h3 class="mb-4 text-xs font-semibold uppercase tracking-wide text-telu-muted">Tautan Profil Akademik (SINTA, ORCID, Scopus, Google Scholar, WoS)</h3>
        @php
            $profilePlatforms = [
                'sinta' => 'SINTA URL',
                'google_scholar' => 'Google Scholar URL',
                'scopus' => 'Scopus ID / URL',
                'orcid' => 'ORCID URL',
                'wos' => 'Web of Science (WoS) URL',
            ];
        @endphp

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($profilePlatforms as $platformKey => $platformLabel)
                <div>
                    <label for="profile_{{ $platformKey }}" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">
                        {{ $platformLabel }}
                    </label>
                    <input
                        type="text"
                        id="profile_{{ $platformKey }}"
                        name="profiles[{{ $platformKey }}]"
                        value="{{ old('profiles.' . $platformKey, isset($profiles[$platformKey]) ? $profiles[$platformKey]->url : '') }}"
                        placeholder="Masukkan URL / ID..."
                        class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                    >
                    @error('profiles.' . $platformKey)
                        <p class="mt-1.5 text-xs text-telu-red">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <p class="mt-6 text-xs text-telu-muted">
            Field yang diubah akan ditandai sebagai koreksi manual dan tidak akan tertimpa saat proses import data otomatis berjalan lagi.
        </p>

        <button type="submit" class="mt-4 rounded-md bg-telu-red px-5 py-2.5 text-sm font-medium text-white hover:bg-telu-red-dark">
            Simpan Koreksi
        </button>
    </form>

    <div class="card-premium mt-6 p-6">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Publikasi</h2>

        <div class="mt-4 space-y-3">
            @forelse ($publications as $publication)
                <form method="POST" action="{{ route('admin.publications.update', [$lecturer, $publication]) }}" class="flex flex-col gap-2 rounded-md border border-telu-border p-3 md:flex-row md:items-center">
                    @csrf
                    @method('PUT')

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $publication->title) }}"
                        class="w-full flex-1 rounded-md border border-telu-border px-3.5 py-2 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                        placeholder="Judul publikasi"
                    >
                    <input
                        type="number"
                        name="year"
                        min="1900"
                        max="{{ now()->year + 1 }}"
                        value="{{ old('year', $publication->year) }}"
                        class="w-full rounded-md border border-telu-border px-3.5 py-2 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red md:w-28"
                        placeholder="Tahun"
                    >

                    <div class="flex gap-2">
                        <button type="submit" class="rounded-md bg-telu-red px-3.5 py-2 text-xs font-medium text-white hover:bg-telu-red-dark">
                            Simpan
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.publications.destroy', [$lecturer, $publication]) }}" class="-mt-2 flex justify-end">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-telu-muted hover:text-telu-red" onclick="return confirm('Hapus publikasi ini?')">
                        Hapus
                    </button>
                </form>
            @empty
                <p class="text-sm text-telu-muted">Belum ada publikasi untuk dosen ini.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('admin.publications.store', $lecturer) }}" class="mt-4 flex flex-col gap-2 rounded-md border border-dashed border-telu-border p-3 md:flex-row md:items-center">
            @csrf

            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                class="w-full flex-1 rounded-md border border-telu-border px-3.5 py-2 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
                placeholder="Judul publikasi baru"
            >
            <input
                type="number"
                name="year"
                min="1900"
                max="{{ now()->year + 1 }}"
                value="{{ old('year') }}"
                class="w-full rounded-md border border-telu-border px-3.5 py-2 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red md:w-28"
                placeholder="Tahun"
            >

            <button type="submit" class="rounded-md bg-telu-ink px-3.5 py-2 text-xs font-medium text-white hover:bg-telu-red">
                Tambah Publikasi
            </button>
        </form>

        @error('title')
            <p class="mt-2 text-xs text-telu-red">{{ $message }}</p>
        @enderror
        @error('year')
            <p class="mt-2 text-xs text-telu-red">{{ $message }}</p>
        @enderror
    </div>
@endsection
