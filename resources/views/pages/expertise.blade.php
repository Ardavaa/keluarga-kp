@extends('layouts.app')

@section('title', 'Peta Keahlian Dosen')

@section('content')
    <x-page-header
        title="Peta Keahlian Dosen"
        subtitle="Dosen Fakultas Informatika dikelompokkan berdasarkan Kelompok Keahlian (KK) riset masing-masing"
    />

    @php
        // Deskripsi deskriptif untuk tiap kelompok keahlian
        $groupDetails = [
            'CITI' => [
                'name' => 'Center for Information Technology Infrastructure',
                'description' => 'Fokus penelitian pada jaringan komputer, cybersecurity, cloud computing, IoT, dan manajemen infrastruktur TI.',
                'badge' => 'bg-rg-citi/10 text-rg-citi ring-rg-citi/20',
                'banner' => 'bg-rg-citi/5 border-l-4 border-rg-citi'
            ],
            'DSIS' => [
                'name' => 'Data Science and Intelligent System',
                'description' => 'Berfokus pada pengolahan data besar, machine learning, natural language processing, data mining, dan sistem cerdas.',
                'badge' => 'bg-rg-dsis/10 text-rg-dsis ring-rg-dsis/20',
                'banner' => 'bg-rg-dsis/5 border-l-4 border-rg-dsis'
            ],
            'SEAL' => [
                'name' => 'Software Engineering and Application',
                'description' => 'Fokus pada metodologi rekayasa perangkat lunak, arsitektur sistem, UI/UX, pengujian, dan pengembangan aplikasi bisnis.',
                'badge' => 'bg-rg-seal/10 text-rg-seal ring-rg-seal/20',
                'banner' => 'bg-rg-seal/5 border-l-4 border-rg-seal'
            ],
            'LAINNYA' => [
                'name' => 'Kelompok Keahlian Lainnya',
                'description' => 'Dosen yang belum tergabung secara resmi atau memiliki fokus di bidang umum lainnya.',
                'badge' => 'bg-rg-unknown/10 text-rg-unknown ring-rg-unknown/20',
                'banner' => 'bg-rg-unknown/5 border-l-4 border-rg-unknown'
            ]
        ];
    @endphp

    <!-- Tab Filter Container using Alpine.js -->
    <div x-data="{ activeTab: 'ALL' }" class="w-full">

        <!-- Tab Navigation Bar -->
        <div class="mb-8 flex flex-wrap items-center gap-2 border-b border-telu-border">
            <button
                @click="activeTab = 'ALL'"
                :class="activeTab === 'ALL' ? 'border-telu-red text-telu-red' : 'border-transparent text-telu-muted hover:text-telu-ink'"
                class="-mb-px border-b-2 px-4 py-2.5 text-sm font-medium cursor-pointer"
            >
                Semua Kelompok
            </button>

            @foreach ($groupedLecturers as $group => $lecturers)
                @php
                    $grpKey = strtoupper(trim($group));
                @endphp
                <button
                    @click="activeTab = '{{ $grpKey }}'"
                    :class="activeTab === '{{ $grpKey }}' ? 'border-telu-red text-telu-red' : 'border-transparent text-telu-muted hover:text-telu-ink'"
                    class="-mb-px border-b-2 px-4 py-2.5 text-sm font-medium cursor-pointer"
                >
                    {{ $grpKey }}
                </button>
            @endforeach
        </div>

        <!-- Tab Content Sections -->
        @forelse ($groupedLecturers as $group => $lecturers)
            @php
                $grpKey = strtoupper(trim($group));
                $details = $groupDetails[$grpKey] ?? $groupDetails['LAINNYA'];
                $accentBorder = match ($grpKey) {
                    'CITI' => 'border-l-rg-citi',
                    'DSIS' => 'border-l-rg-dsis',
                    'SEAL' => 'border-l-rg-seal',
                    default => 'border-l-rg-unknown',
                };
            @endphp

            <div
                x-show="activeTab === 'ALL' || activeTab === '{{ $grpKey }}'"
                class="mb-12"
            >
                <!-- Group Info Banner -->
                <div class="card-premium border-l-4 {{ $accentBorder }} p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1 max-w-2xl">
                        <div class="flex items-center gap-3">
                            <h3 class="text-base font-semibold tracking-tight text-telu-ink uppercase">{{ $grpKey }}</h3>
                            <x-research-group-badge :group="$grpKey" />
                        </div>
                        <h4 class="text-sm font-semibold text-telu-ink">{{ $details['name'] }}</h4>
                        <p class="text-xs text-telu-muted leading-relaxed">{{ $details['description'] }}</p>
                    </div>

                    <div class="shrink-0 self-start md:self-auto text-sm text-telu-muted">
                        <strong class="text-lg font-semibold text-telu-ink">{{ $lecturers->count() }}</strong> Dosen
                    </div>
                </div>

                <!-- Lecturers Cards Grid -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-6">
                    @foreach ($lecturers as $lecturer)
                        <x-lecturer-card :lecturer="$lecturer" />
                    @endforeach
                </div>
            </div>
        @empty
            <x-empty-state title="Belum Ada Data" message="Tidak ditemukan data keahlian dosen FIF." />
        @endforelse
    </div>
@endsection
