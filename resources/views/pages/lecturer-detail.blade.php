@extends('layouts.app')

@section('title', $lecturer->name_with_title ?: $lecturer->full_name)

@php
    $profilesByPlatform = $lecturer->profiles->keyBy('platform');
    $platformLabels = [
        'sinta' => 'SINTA',
        'google_scholar' => 'Google Scholar',
        'scopus' => 'Scopus',
        'orcid' => 'ORCID',
        'wos' => 'Web of Science',
    ];

    $translateReason = function($reason) {
        if (str_starts_with($reason, 'Fellow members of the ')) {
            $group = str_replace(['Fellow members of the ', ' Research Group'], '', $reason);
            return 'Sama-sama anggota Kelompok Keahlian ' . $group;
        }
        if (str_starts_with($reason, 'Cross-disciplinary potential: connecting ')) {
            $groups = str_replace('Cross-disciplinary potential: connecting ', '', $reason);
            return 'Potensi lintas disiplin: menghubungkan ' . str_replace(' and ', ' dan ', $groups);
        }
        if (str_starts_with($reason, 'Both specialize in ')) {
            $field = str_replace('Both specialize in ', '', $reason);
            return 'Sama-sama memiliki keahlian di bidang ' . $field;
        }
        if (str_starts_with($reason, 'Shared research themes: ')) {
            $themes = str_replace('Shared research themes: ', '', $reason);
            return 'Tema penelitian bersama: ' . $themes;
        }
        if (str_starts_with($reason, 'Very high publication topic overlap ')) {
            $sim = str_replace('Very high publication topic overlap (similarity: ', '', $reason);
            $sim = rtrim($sim, ')');
            return 'Tingkat kesamaan topik publikasi sangat tinggi (kemiripan: ' . $sim . ')';
        }
        if (str_starts_with($reason, 'Moderate publication theme match ')) {
            $sim = str_replace('Moderate publication theme match (similarity: ', '', $reason);
            $sim = rtrim($sim, ')');
            return 'Kesesuaian tema publikasi sedang (kemiripan: ' . $sim . ')';
        }
        if ($reason === 'Have previously co-authored research papers together') {
            return 'Pernah menulis artikel ilmiah bersama sebelumnya';
        }
        if (str_starts_with($reason, 'Both have collaborated with shared co-authors: ')) {
            $authors = str_replace('Both have collaborated with shared co-authors: ', '', $reason);
            return 'Sama-sama pernah berkolaborasi dengan penulis yang sama: ' . $authors;
        }
        return $reason;
    };
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
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($lecturer->recommendationsGiven->take(10) as $rec)
                    @php
                        $partner = $rec->recommendedLecturer;
                        $partnerInitials = collect(explode(' ', $partner->full_name))
                            ->take(2)
                            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                            ->join('');
                        $partnerGroup = strtoupper(trim((string) $partner->research_group));
                        $partnerAvatarBg = match ($partnerGroup) {
                            'CITI' => 'bg-rg-citi/10 text-rg-citi border border-rg-citi/20',
                            'DSIS' => 'bg-rg-dsis/10 text-rg-dsis border border-rg-dsis/20',
                            'SEAL' => 'bg-rg-seal/10 text-rg-seal border border-rg-seal/20',
                            default => 'bg-telu-bg-soft text-telu-muted border border-telu-border/40',
                        };
                    @endphp
                    <a
                        href="{{ route('lecturers.show', $partner) }}"
                        class="group card-premium block p-5 hover:border-telu-red/50"
                    >
                        <div class="flex items-start gap-4">
                            <!-- Partner Photo / Avatar -->
                            <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-[0.625rem] font-semibold text-sm uppercase tracking-wide overflow-hidden {{ $partnerAvatarBg }}">
                                @if ($partner->photo)
                                    <img src="{{ $partner->photo }}" alt="{{ $partner->full_name }}" class="absolute inset-0 h-full w-full object-cover" onerror="this.remove()">
                                @endif
                                <span>{{ $partnerInitials }}</span>
                            </div>

                            <!-- Partner Details -->
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <h4 class="font-semibold text-sm sm:text-base text-telu-ink group-hover:text-telu-red truncate" title="{{ $partner->name_with_title ?: $partner->full_name }}">
                                        {{ $partner->name_with_title ?: $partner->full_name }}
                                    </h4>
                                    <span class="shrink-0 text-sm font-semibold text-telu-red bg-telu-red/5 px-2 py-0.5 rounded-full" title="Skor kecocokan">
                                        {{ number_format($rec->score * 100, 2) }}%
                                    </span>
                                </div>
                                <p class="text-xs text-telu-muted mt-0.5">
                                    {{ $partner->academic_rank ?: 'Dosen FIF' }} &middot; Kode: {{ $partner->lecturer_code ?: '—' }}
                                </p>
                                <p class="text-xs text-telu-body mt-2 truncate" title="{{ $partner->field ?: 'Umum' }}">
                                    Bidang: <span class="text-telu-ink font-medium">{{ $partner->field ?: 'Umum' }}</span>
                                </p>
                                <div class="mt-3 flex items-center justify-between text-xs text-telu-muted border-t border-telu-border/50 pt-2">
                                    <span class="truncate max-w-[150px]" title="{{ $partner->study_program }}">{{ $partner->study_program ?: 'FIF Tel-U' }}</span>
                                    <x-research-group-badge :group="$partner->research_group" />
                                </div>
                            </div>
                        </div>

                        <!-- Reasons for recommendation -->
                        @if (!empty($rec->reasons))
                            <div class="mt-4 flex flex-wrap gap-1.5 border-t border-dashed border-telu-border pt-3">
                                @foreach (array_slice($rec->reasons, 0, 3) as $reason)
                                    <span class="inline-flex items-center rounded bg-telu-bg-soft-2 px-2 py-0.5 text-[10px] text-telu-body font-medium">
                                        {{ $translateReason($reason) }}
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
