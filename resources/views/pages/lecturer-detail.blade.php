@extends('layouts.app')

@section('title', $lecturer->name_with_title ?: $lecturer->name)

@php
    $profilesByPlatform = $lecturer->profiles->keyBy('platform');
    $platformLabels = [
        'sinta' => 'SINTA',
        'google_scholar' => 'Google Scholar',
        'scopus' => 'Scopus',
        'orcid' => 'ORCID',
    ];
@endphp

@section('content')
    <a href="{{ route('lecturers.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-telu-muted hover:text-telu-red">
        &larr; Kembali ke daftar dosen
    </a>

    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold text-telu-ink">{{ $lecturer->name_with_title ?: $lecturer->name }}</h1>
                <x-research-group-badge :group="$lecturer->research_group" />
            </div>
            <p class="mt-1 text-sm text-telu-muted">
                {{ $lecturer->field ?: '—' }} &middot; {{ $lecturer->study_program ?: '—' }}
                @if ($lecturer->academic_rank)
                    &middot; {{ $lecturer->academic_rank }}
                @endif
            </p>
            @if ($lecturer->lecturer_code)
                <p class="mt-1 text-xs text-telu-muted">Kode: {{ $lecturer->lecturer_code }} &middot; NIP: {{ $lecturer->code }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-kpi-card label="Sitasi" :value="$lecturer->citation_count" />
        <x-kpi-card label="H-Index" :value="$lecturer->h_index" />
        <x-kpi-card label="i10-Index" :value="$lecturer->i10_index" />
    </div>

    <div class="mt-8">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Profil &amp; Tautan</h2>
        <div class="mt-3 flex flex-wrap gap-3">
            @foreach ($platformLabels as $platform => $label)
                @if ($profilesByPlatform->has($platform))
                    <a
                        href="{{ $profilesByPlatform[$platform]->url }}"
                        target="_blank"
                        rel="noopener"
                        class="rounded-md border border-telu-border px-3 py-1.5 text-sm text-telu-red hover:border-telu-red/40"
                    >
                        {{ $label }}
                    </a>
                @else
                    <span class="rounded-md border border-dashed border-telu-border px-3 py-1.5 text-sm text-telu-muted">
                        {{ $label }} (belum ditautkan)
                    </span>
                @endif
            @endforeach
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Minat Riset &amp; Kata Kunci</h2>

            @if ($lecturer->researchInterests->isNotEmpty())
                <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-telu-body">
                    @foreach ($lecturer->researchInterests as $interest)
                        <li>{{ $interest->interest }}</li>
                    @endforeach
                </ul>
            @endif

            @if ($lecturer->keywords->isNotEmpty())
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($lecturer->keywords as $keyword)
                        <span class="rounded-full bg-telu-bg-soft-2 px-2.5 py-0.5 text-xs text-telu-body">
                            {{ $keyword->keyword }}
                        </span>
                    @endforeach
                </div>
            @endif

            @if ($lecturer->researchInterests->isEmpty() && $lecturer->keywords->isEmpty())
                <p class="mt-3 text-sm text-telu-muted">Belum ada data minat riset/kata kunci.</p>
            @endif
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Publikasi ({{ $lecturer->publications->count() }})</h2>

            @if ($lecturer->publications->isNotEmpty())
                <ul class="mt-3 space-y-3 text-sm">
                    @foreach ($lecturer->publications as $publication)
                        <li class="border-b border-telu-border pb-2 last:border-b-0">
                            <p class="text-telu-body">{{ $publication->title }}</p>
                            <p class="mt-0.5 text-xs text-telu-muted">{{ $publication->year ?? 'Tahun tidak diketahui' }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-3 text-sm text-telu-muted">Belum ada publikasi tercatat.</p>
            @endif
        </div>
    </div>
@endsection
