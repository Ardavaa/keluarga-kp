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
        <div class="flex flex-wrap items-center gap-2 mb-8 bg-white border border-telu-border/30 rounded-2xl p-2 shadow-sm">
            <button 
                @click="activeTab = 'ALL'" 
                :class="activeTab === 'ALL' ? 'bg-telu-navy text-white shadow-sm' : 'text-telu-body hover:bg-telu-bg-soft/75 hover:text-telu-ink'"
                class="rounded-xl px-4 py-2.5 text-xs font-bold transition-all duration-200 cursor-pointer"
            >
                Semua Kelompok
            </button>

            @foreach ($groupedLecturers as $group => $lecturers)
                @php
                    $grpKey = strtoupper(trim($group));
                    $btnClass = match ($grpKey) {
                        'CITI' => "activeTab === 'CITI' ? 'bg-rg-citi/15 text-rg-citi ring-1 ring-rg-citi/30 font-bold' : 'text-telu-body hover:bg-telu-bg-soft/75 hover:text-telu-ink'",
                        'DSIS' => "activeTab === 'DSIS' ? 'bg-rg-dsis/15 text-rg-dsis ring-1 ring-rg-dsis/30 font-bold' : 'text-telu-body hover:bg-telu-bg-soft/75 hover:text-telu-ink'",
                        'SEAL' => "activeTab === 'SEAL' ? 'bg-rg-seal/15 text-rg-seal ring-1 ring-rg-seal/30 font-bold' : 'text-telu-body hover:bg-telu-bg-soft/75 hover:text-telu-ink'",
                        default => "activeTab === '$grpKey' ? 'bg-rg-unknown/15 text-rg-unknown ring-1 ring-rg-unknown/30 font-bold' : 'text-telu-body hover:bg-telu-bg-soft/75 hover:text-telu-ink'",
                    };
                @endphp
                <button 
                    @click="activeTab = '{{ $grpKey }}'" 
                    :class="{!! $btnClass !!}"
                    class="rounded-xl px-4 py-2.5 text-xs font-semibold transition-all duration-200 cursor-pointer"
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
            @endphp
            
            <div 
                x-show="activeTab === 'ALL' || activeTab === '{{ $grpKey }}'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="mb-12"
            >
                <!-- Group Info Banner -->
                <div class="rounded-2xl p-6 shadow-sm border border-telu-border/20 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $details['banner'] }}">
                    <div class="space-y-1 max-w-2xl">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold tracking-tight text-telu-ink uppercase">{{ $grpKey }}</h3>
                            <span class="inline-flex rounded-lg px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset {{ $details['badge'] }}">
                                {{ $grpKey }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-telu-ink/90">{{ $details['name'] }}</h4>
                        <p class="text-xs text-telu-muted leading-relaxed">{{ $details['description'] }}</p>
                    </div>
                    
                    <div class="shrink-0 flex items-center gap-2 bg-white/80 rounded-xl px-4 py-2.5 border border-telu-border/20 self-start md:self-auto shadow-sm">
                        <span class="text-lg font-extrabold text-telu-ink">{{ $lecturers->count() }}</span>
                        <span class="text-xs font-semibold text-telu-muted">Dosen Aktif</span>
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
            <div class="rounded-2xl border border-telu-border/30 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto h-12 w-12 text-telu-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="mt-4 text-base font-semibold text-telu-ink">Belum Ada Data</p>
                <p class="mt-1 text-sm text-telu-muted">Tidak ditemukan data keahlian dosen FIF.</p>
            </div>
        @endforelse
    </div>
@endsection
