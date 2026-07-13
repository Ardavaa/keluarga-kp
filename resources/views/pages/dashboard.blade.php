@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
    <x-page-header
        title="Dashboard Utama"
        subtitle="Ringkasan jumlah dosen, publikasi, bidang AI, dan kolaborasi"
    />

    <div class="mb-6 flex items-center justify-end">
        <x-export-buttons excel-route="dashboard.export.excel" pdf-route="dashboard.export.pdf" />
    </div>

    <div class="mb-10 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-kpi-card
            label="Total Dosen"
            :value="$totalLecturers"
            type="red"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>'
        />
        <x-kpi-card
            label="Total Publikasi"
            :value="$totalPublications"
            type="navy"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>'
        />
        <x-kpi-card
            label="Kategori AI"
            :value="$totalAiFields"
            type="green"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>'
        />
        <x-kpi-card
            label="Koneksi Kolaborasi"
            :value="$totalCollaborations"
            type="blue"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>'
        />
    </div>

    <div class="mb-10">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-telu-muted">Kelompok Keahlian (KK) FIF</h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="card-premium border-l-4 border-l-rg-citi p-5">
                <div class="flex items-center justify-between">
                    <x-research-group-badge group="CITI" />
                    <span class="text-sm text-telu-muted">
                        <strong class="text-lg font-semibold text-telu-ink">{{ $researchGroups['CITI'] ?? 0 }}</strong> Dosen
                    </span>
                </div>
                <h3 class="mt-3 text-sm font-semibold text-telu-ink">Center for Information Technology Infrastructure</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-telu-muted">
                    Jaringan komputer, cybersecurity, komputasi awan, IoT, sistem terdistribusi, dan manajemen infrastruktur TI.
                </p>
            </div>

            <div class="card-premium border-l-4 border-l-rg-dsis p-5">
                <div class="flex items-center justify-between">
                    <x-research-group-badge group="DSIS" />
                    <span class="text-sm text-telu-muted">
                        <strong class="text-lg font-semibold text-telu-ink">{{ $researchGroups['DSIS'] ?? 0 }}</strong> Dosen
                    </span>
                </div>
                <h3 class="mt-3 text-sm font-semibold text-telu-ink">Data Science and Intelligent System</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-telu-muted">
                    Pengolahan data skala besar, machine learning, natural language processing, data mining, dan sistem cerdas.
                </p>
            </div>

            <div class="card-premium border-l-4 border-l-rg-seal p-5">
                <div class="flex items-center justify-between">
                    <x-research-group-badge group="SEAL" />
                    <span class="text-sm text-telu-muted">
                        <strong class="text-lg font-semibold text-telu-ink">{{ $researchGroups['SEAL'] ?? 0 }}</strong> Dosen
                    </span>
                </div>
                <h3 class="mt-3 text-sm font-semibold text-telu-ink">Software Engineering and Application</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-telu-muted">
                    Metodologi rekayasa perangkat lunak, arsitektur sistem, UI/UX, pengujian, dan pengembangan aplikasi.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card-premium p-6">
            <div class="mb-3 flex items-center justify-between border-b border-telu-border pb-3">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Publikasi Terbaru</h2>
                <a href="{{ route('lecturers.index') }}" class="text-xs font-medium text-telu-red hover:underline">Semua dosen</a>
            </div>

            <div class="divide-y divide-telu-border">
                @forelse ($recentPublications as $pub)
                    <div class="flex items-start justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="line-clamp-2 text-sm text-telu-ink">{{ $pub->title }}</p>
                            <a href="{{ route('lecturers.show', $pub->lecturer) }}" class="mt-0.5 block text-xs text-telu-muted hover:text-telu-red">
                                {{ $pub->lecturer->name }}
                            </a>
                        </div>
                        <span class="shrink-0 text-xs text-telu-muted">{{ $pub->year }}</span>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-telu-muted">Belum ada aktivitas publikasi riset terbaru.</p>
                @endforelse
            </div>
        </div>

        <div class="card-premium p-6">
            <div class="mb-3 flex items-center justify-between border-b border-telu-border pb-3">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Kolaborasi Teraktif</h2>
                <a href="{{ route('collaborations.index') }}" class="text-xs font-medium text-telu-red hover:underline">Lihat graph</a>
            </div>

            <div class="divide-y divide-telu-border">
                @forelse ($topCollaborations as $collab)
                    <div class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <div class="min-w-0 text-sm">
                            <a href="{{ route('lecturers.show', $collab->lecturerOne) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                {{ $collab->lecturerOne->name }}
                            </a>
                            <span class="text-telu-muted">&amp;</span>
                            <a href="{{ route('lecturers.show', $collab->lecturerTwo) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                {{ $collab->lecturerTwo->name }}
                            </a>
                        </div>
                        <span class="shrink-0 text-xs text-telu-muted">{{ $collab->collaboration_count }} publikasi</span>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-telu-muted">Belum ada data kolaborasi tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
