@extends('layouts.app')

@section('title', 'Koreksi Data Dosen')

@section('content')
    <x-page-header
        title="Koreksi Data Dosen"
        subtitle="Cari dosen yang datanya perlu dikoreksi"
    />

    <a href="{{ route('admin.dashboard') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-telu-muted hover:text-telu-red">
        &larr; Kembali ke Panel Admin
    </a>

    <form method="GET" action="{{ route('admin.lecturers.index') }}" class="card-premium p-6">
        <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Cari</label>
        <div class="flex gap-3">
            <input
                type="text"
                id="search"
                name="search"
                value="{{ $search }}"
                placeholder="Nama, kode, atau NIP..."
                class="w-full rounded-md border border-telu-border px-3.5 py-2.5 text-sm text-telu-ink focus:border-telu-red focus:outline-none focus:ring-1 focus:ring-telu-red"
            >
            <button type="submit" class="shrink-0 rounded-md bg-telu-red px-5 py-2.5 text-sm font-medium text-white hover:bg-telu-red-dark">
                Cari
            </button>
        </div>
    </form>

    <div class="card-premium mt-6 p-6">
        @if ($lecturers->isEmpty())
            <p class="text-sm text-telu-muted">Tidak ada dosen yang cocok.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-telu-border text-left text-xs font-semibold uppercase tracking-wide text-telu-muted">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Prodi</th>
                            <th class="px-4 py-3">KK</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-telu-border">
                        @foreach ($lecturers as $lecturer)
                            <tr class="hover:bg-telu-bg-soft">
                                <td class="px-4 py-3 font-medium text-telu-ink">{{ $lecturer->full_name }}</td>
                                <td class="px-4 py-3 text-telu-muted">{{ $lecturer->lecturer_code ?: '—' }}</td>
                                <td class="px-4 py-3 text-telu-muted">{{ $lecturer->study_program ?: '—' }}</td>
                                <td class="px-4 py-3 text-telu-muted">{{ $lecturer->research_group ?: '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.lecturers.edit', $lecturer) }}" class="text-sm font-medium text-telu-red hover:underline">
                                        Koreksi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $lecturers->links() }}
            </div>
        @endif
    </div>
@endsection
