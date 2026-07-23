@extends('layouts.app')

@section('title', $lecturer->name_with_title ?: $lecturer->full_name)

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

    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center">
        <!-- Lecturer Photo / Avatar (Portrait Aspect Ratio to Prevent Stretching) -->
        @php
            $initials = collect(explode(' ', $lecturer->full_name))
                ->take(2)
                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                ->join('');
            $group = strtoupper(trim((string) $lecturer->research_group));
            $avatarBg = match ($group) {
                'CITI' => 'bg-rg-citi/10 text-rg-citi border border-rg-citi/20',
                'DSIS' => 'bg-rg-dsis/10 text-rg-dsis border border-rg-dsis/20',
                'SEAL' => 'bg-rg-seal/10 text-rg-seal border border-rg-seal/20',
                default => 'bg-telu-bg-soft text-telu-muted border border-telu-border/40',
            };
        @endphp
        <div class="relative flex h-28 w-20 shrink-0 items-center justify-center rounded-xl font-semibold text-xl uppercase tracking-wide overflow-hidden {{ $avatarBg }}">
            @if ($lecturer->photo)
                <img src="{{ $lecturer->photo }}" alt="{{ $lecturer->full_name }}" class="absolute inset-0 h-full w-full object-cover" onerror="this.remove()">
            @endif
            <span>{{ $initials }}</span>
        </div>

        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold text-telu-ink">{{ $lecturer->name_with_title ?: $lecturer->full_name }}</h1>
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
                    @php
                        $profileUrl = $profilesByPlatform[$platform]->url;
                        if ($platform === 'scopus' && ! str_starts_with($profileUrl, 'http')) {
                            $profileUrl = 'https://www.scopus.com/authid/detail.uri?authorId=' . $profileUrl;
                        }
                    @endphp
                    <a
                        href="{{ $profileUrl }}"
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

    <!-- Collaboration Recommendations Section -->
    @if ($lecturer->recommendationsGiven->isNotEmpty())
        <div class="mt-8">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Rekomendasi Partner Kolaborasi</h2>
            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($lecturer->recommendationsGiven as $rec)
                    @php
                        $partner = $rec->recommendedLecturer;
                    @endphp
                    <a
                        href="{{ route('lecturers.show', $partner) }}"
                        class="group card-premium block p-4 hover:border-telu-red/50"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="font-semibold text-sm text-telu-ink group-hover:text-telu-red truncate">
                                {{ $partner->name_with_title ?: $partner->full_name }}
                            </h4>
                            <span class="shrink-0 text-sm font-semibold text-telu-red">{{ number_format($rec->score, 2) }}</span>
                        </div>
                        <p class="mt-0.5 text-xs text-telu-muted">Skor kecocokan</p>
                        @if (!empty($rec->reasons))
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach (array_slice($rec->reasons, 0, 2) as $reason)
                                    <span class="inline-flex items-center rounded bg-telu-bg-soft-2 px-1.5 py-0.5 text-[11px] text-telu-body">
                                        {{ $reason }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Minat Riset &amp; Kata Kunci</h2>

            @if ($lecturer->researchInterests->isNotEmpty())
                <div x-data="{ expanded: false }">
                    <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-telu-body">
                        @foreach ($lecturer->researchInterests as $index => $interest)
                            <li
                                @if ($index >= 5)
                                    x-show="expanded"
                                    x-cloak
                                @endif
                            >
                                {{ $interest->interest }}
                            </li>
                        @endforeach
                    </ul>
                    @if ($lecturer->researchInterests->count() > 5)
                        <button
                            @click="expanded = !expanded"
                            class="mt-2 text-xs font-semibold text-telu-red hover:underline focus:outline-none"
                            x-text="expanded ? 'Tampilkan lebih sedikit' : 'Tampilkan lebih banyak (' + {{ $lecturer->researchInterests->count() - 5 }} + ' lagi)'"
                        ></button>
                    @endif
                </div>
            @endif

            @if ($lecturer->keywords->isNotEmpty())
                <div class="mt-4" x-data="{ expanded: false }">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($lecturer->keywords as $index => $keyword)
                            <span
                                class="rounded-full bg-telu-bg-soft-2 px-2.5 py-0.5 text-xs text-telu-body"
                                @if ($index >= 12)
                                    x-show="expanded"
                                    x-cloak
                                @endif
                            >
                                {{ $keyword->keyword }}
                            </span>
                        @endforeach
                    </div>
                    @if ($lecturer->keywords->count() > 12)
                        <button
                            @click="expanded = !expanded"
                            class="mt-2 text-xs font-semibold text-telu-red hover:underline focus:outline-none"
                            x-text="expanded ? 'Tampilkan lebih sedikit' : 'Tampilkan lebih banyak (' + {{ $lecturer->keywords->count() - 12 }} + ' lagi)'"
                        ></button>
                    @endif
                </div>
            @endif

            @if ($lecturer->researchInterests->isEmpty() && $lecturer->keywords->isEmpty())
                <p class="mt-3 text-sm text-telu-muted">Belum ada data minat riset/kata kunci.</p>
            @endif
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Publikasi ({{ $lecturer->publications->count() }})</h2>

            @if ($lecturer->publications->isNotEmpty())
                <div x-data="{ expanded: false }">
                    <ul class="mt-3 space-y-3 text-sm">
                        @foreach ($lecturer->publications as $index => $publication)
                            <li
                                class="border-b border-telu-border pb-2 last:border-b-0"
                                @if ($index >= 5)
                                    x-show="expanded"
                                    x-cloak
                                @endif
                            >
                                <p class="text-telu-body">{{ $publication->title }}</p>
                                <p class="mt-0.5 text-xs text-telu-muted">{{ $publication->year ?? 'Tahun tidak diketahui' }}</p>
                            </li>
                        @endforeach
                    </ul>
                    @if ($lecturer->publications->count() > 5)
                        <button
                            @click="expanded = !expanded"
                            class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-telu-red hover:underline focus:outline-none"
                            x-text="expanded ? 'Tampilkan lebih sedikit' : 'Tampilkan semua (' + {{ $lecturer->publications->count() }} + ' publikasi)'"
                        ></button>
                    @endif
                </div>
            @else
                <p class="mt-3 text-sm text-telu-muted">Belum ada publikasi tercatat.</p>
            @endif
        </div>
    </div>
@endsection
