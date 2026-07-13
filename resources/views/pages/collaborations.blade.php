@extends('layouts.app')

@section('title', 'Kolaborasi')

@section('content')
    <x-page-header
        title="Kolaborasi Dosen"
        subtitle="Visualisasi jaringan kerja sama dan publikasi bersama antardosen Fakultas Informatika"
    />

    @if ($collaborations->isEmpty())
        <div class="rounded-2xl border border-telu-border/30 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-telu-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="mt-4 text-base font-semibold text-telu-ink">Belum Ada Data</p>
            <p class="mt-1 text-sm text-telu-muted">Belum ada catatan data kolaborasi publikasi riset antardosen saat ini.</p>
        </div>
    @else
        <div class="mb-6 flex items-center justify-end">
            <x-export-buttons excel-route="collaborations.export.excel" pdf-route="collaborations.export.pdf" />
        </div>

        <!-- Graph Card -->
        <div class="card-premium flex flex-col bg-white p-6 md:p-8 shadow-sm border border-telu-border/30">
            <div class="w-full border-b border-telu-border/10 pb-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-telu-ink">Jejaring Jurnal Riset</h3>
                    <p class="text-xs text-telu-muted">Menyajikan grafik keterhubungan dosen FIF dalam publikasi ilmiah</p>
                </div>
                
                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-3.5 text-xs font-semibold text-telu-body">
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-citi"></span> CITI</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-dsis"></span> DSIS</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-seal"></span> SEAL</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-unknown"></span> Lainnya</span>
                </div>
            </div>

            <!-- Network Canvas -->
            <div class="relative mt-6">
                <div id="collaboration-network" class="h-[460px] w-full rounded-2xl bg-telu-bg-soft/50 border border-telu-border/20 shadow-inner"></div>
            </div>

            <!-- Interaction Tip -->
            <div class="mt-4 flex items-center gap-2 text-xs text-telu-muted/80 bg-telu-bg-soft px-4 py-2.5 rounded-xl border border-telu-border/10">
                <svg class="h-4 w-4 text-telu-muted/70 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><strong>Tips navigasi:</strong> Gunakan scroll mouse / cubit layar untuk memperbesar/memperkecil grafik. Drag mouse untuk bergeser, dan klik / layangkan kursor pada bulatan dosen untuk melihat nama dan keterhubungannya.</span>
            </div>
        </div>

        <!-- Table List Card -->
        <div class="card-premium bg-white p-6 md:p-8 shadow-sm border border-telu-border/30 mt-8">
            <div class="w-full border-b border-telu-border/10 pb-4 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-telu-ink">Daftar Hubungan Kerja Sama</h3>
                    <p class="text-xs text-telu-muted">Rincian nama pasangan kolaborator beserta daftar tulisan bersamanya</p>
                </div>
                <span class="inline-flex rounded-xl bg-telu-navy/10 px-2.5 py-1 text-xs font-bold text-telu-navy">
                    {{ $collaborations->count() }} Koneksi
                </span>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-telu-border/20 text-left text-xs font-bold uppercase tracking-wider text-telu-muted bg-telu-bg-soft/40">
                            <th class="px-4 py-3 font-semibold rounded-l-xl">Dosen Pertama</th>
                            <th class="px-4 py-3 font-semibold">Dosen Kedua</th>
                            <th class="px-4 py-3 text-right font-semibold rounded-r-xl">Publikasi Bersama</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-telu-border/10">
                        @foreach ($collaborations as $collaboration)
                            @php
                                $init1 = collect(explode(' ', $collaboration->lecturerOne->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                                $init2 = collect(explode(' ', $collaboration->lecturerTwo->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                            @endphp
                            <tr class="hover:bg-telu-bg-soft/20 transition-colors">
                                <!-- Lecturer 1 Column -->
                                <td class="px-4 py-4.5 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-telu-navy text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            {{ $init1 }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <a href="{{ route('lecturers.show', $collaboration->lecturerOne) }}" class="font-bold text-telu-ink hover:text-telu-red hover:underline truncate">
                                                {{ $collaboration->lecturerOne->name }}
                                            </a>
                                            <span class="text-[10px] font-semibold text-telu-muted">KK: {{ $collaboration->lecturerOne->research_group }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Lecturer 2 Column -->
                                <td class="px-4 py-4.5 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-telu-red text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            {{ $init2 }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <a href="{{ route('lecturers.show', $collaboration->lecturerTwo) }}" class="font-bold text-telu-ink hover:text-telu-red hover:underline truncate">
                                                {{ $collaboration->lecturerTwo->name }}
                                            </a>
                                            <span class="text-[10px] font-semibold text-telu-muted">KK: {{ $collaboration->lecturerTwo->research_group }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Collaboration Details/Dropdown Column -->
                                <td class="px-4 py-4.5 text-right align-middle">
                                    <details class="group">
                                        <summary class="flex items-center justify-end gap-1.5 cursor-pointer list-none text-xs font-bold text-telu-red hover:text-telu-red-dark transition-colors select-none">
                                            <span>{{ $collaboration->collaboration_count }} Publikasi</span>
                                            <svg class="h-4 w-4 text-telu-red transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="mt-3.5 text-left bg-telu-bg-soft rounded-xl p-4 border border-telu-border/20 shadow-inner max-w-md ml-auto">
                                            <ul class="space-y-2 text-xs text-telu-body list-disc pl-4">
                                                @foreach ($collaboration->shared_publications ?? [] as $title)
                                                    <li class="leading-relaxed text-telu-ink/90 font-medium">"{{ $title }}"</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const rawNodes = @json($nodes);
                    const totalNodes = rawNodes.length;
                    const radius = 150; // Radius lingkaran diagram

                    // Hitung koordinat lingkaran untuk masing-masing dosen
                    const nodes = rawNodes.map((node, index) => {
                        const angle = (index / totalNodes) * 2 * Math.PI;
                        return {
                            id: node.id,
                            label: node.label,
                            group: node.group,
                            // Atur posisi melingkar statis
                            x: radius * Math.cos(angle),
                            y: radius * Math.sin(angle),
                            // Ukuran bulatan proporsional terhadap keaktifan kolaborasinya
                            size: 12 + (node.value * 3.5)
                        };
                    });

                    // Cincin warna kelompok keahlian (Hollow Nodes dengan background putih)
                    const groupColors = {
                        CITI: {
                            color: { 
                                background: '#ffffff', 
                                border: '#FF6B6B', 
                                highlight: { background: '#ffffff', border: '#e05555' } 
                            }
                        },
                        DSIS: {
                            color: { 
                                background: '#ffffff', 
                                border: '#6BCB77', 
                                highlight: { background: '#ffffff', border: '#4fa85c' } 
                            }
                        },
                        SEAL: {
                            color: { 
                                background: '#ffffff', 
                                border: '#4D96FF', 
                                highlight: { background: '#ffffff', border: '#3178d6' } 
                            }
                        },
                        Lainnya: {
                            color: { 
                                background: '#ffffff', 
                                border: '#9E9E9E', 
                                highlight: { background: '#ffffff', border: '#7d7d7d' } 
                            }
                        },
                    };

                    new VisNetwork(
                        document.getElementById('collaboration-network'),
                        {
                            nodes: nodes,
                            edges: @json($edges),
                        },
                        {
                            nodes: {
                                shape: 'dot',
                                font: { face: 'Inter', size: 11, color: '#222222', bold: { color: '#222222' } },
                                borderWidth: 3.5,
                                shadow: { enabled: true, color: 'rgba(0,0,0,0.06)', size: 4, x: 1, y: 1 }
                            },
                            edges: {
                                color: { color: 'rgba(0, 33, 71, 0.12)', highlight: '#9F1521', hover: '#9F1521' },
                                smooth: { enabled: true, type: 'curvedCW', roundness: 0.18 },
                                width: 1.5
                            },
                            groups: groupColors,
                            physics: { 
                                enabled: false // Matikan fisika agar node tetap berada di posisi koordinat melingkar yang rapi
                            },
                            interaction: { hover: true, tooltipDelay: 100, zoomView: true, dragView: true },
                        }
                    );
                });
            </script>
        @endpush
    @endif
@endsection
