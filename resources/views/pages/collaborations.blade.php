@extends('layouts.app')

@section('title', 'Kolaborasi')

@section('content')
    <x-page-header
        title="Kolaborasi Dosen"
        subtitle="Visualisasi jaringan kerja sama dan publikasi bersama antardosen Fakultas Informatika"
    />

    @if ($collaborations->isEmpty())
        <x-empty-state title="Belum Ada Data" message="Belum ada catatan data kolaborasi publikasi riset antardosen saat ini." />
    @else
        <div class="mb-6 flex items-center justify-end">
            <x-export-buttons excel-route="collaborations.export.excel" pdf-route="collaborations.export.pdf" />
        </div>

        <!-- Graph Card -->
        <div class="card-premium flex flex-col p-6">
            <div class="w-full border-b border-telu-border pb-3 flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Jejaring Riset Dosen</h2>

                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-3.5 text-xs text-telu-body">
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-citi"></span> CITI</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-dsis"></span> DSIS</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-seal"></span> SEAL</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rg-unknown"></span> Lainnya</span>
                </div>
            </div>

            <!-- Network Canvas -->
            <div class="relative mt-6">
                <div id="collaboration-network" class="h-[460px] w-full rounded-md bg-telu-bg-soft border border-telu-border"></div>
            </div>

            <!-- Interaction Tip -->
            <p class="mt-3 text-xs text-telu-muted">
                Scroll untuk zoom, drag untuk menggeser, klik/hover bulatan dosen untuk melihat nama dan keterhubungannya.
            </p>
        </div>

        <!-- Table List Card -->
        <div class="card-premium p-6 mt-8">
            <div class="w-full border-b border-telu-border pb-3 flex items-center justify-between gap-3">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Daftar Hubungan Kerja Sama</h2>
                <span class="text-xs text-telu-muted">{{ $collaborations->count() }} koneksi</span>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-telu-border text-left text-xs font-semibold uppercase tracking-wide text-telu-muted">
                            <th class="px-4 py-3">Dosen Pertama</th>
                            <th class="px-4 py-3">Dosen Kedua</th>
                            <th class="px-4 py-3 text-right">Publikasi Bersama</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-telu-border">
                        @foreach ($collaborations as $collaboration)
                            <tr class="hover:bg-telu-bg-soft">
                                <!-- Lecturer 1 Column -->
                                <td class="px-4 py-4 align-middle">
                                    <a href="{{ route('lecturers.show', $collaboration->lecturerOne) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                        {{ $collaboration->lecturerOne->full_name }}
                                    </a>
                                    <span class="block text-xs text-telu-muted">KK: {{ $collaboration->lecturerOne->research_group }}</span>
                                </td>

                                <!-- Lecturer 2 Column -->
                                <td class="px-4 py-4 align-middle">
                                    <a href="{{ route('lecturers.show', $collaboration->lecturerTwo) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                        {{ $collaboration->lecturerTwo->full_name }}
                                    </a>
                                    <span class="block text-xs text-telu-muted">KK: {{ $collaboration->lecturerTwo->research_group }}</span>
                                </td>

                                <!-- Collaboration Details/Dropdown Column -->
                                <td class="px-4 py-4 text-right align-middle">
                                    <details class="group">
                                        <summary class="flex items-center justify-end gap-1.5 cursor-pointer list-none text-xs font-medium text-telu-red hover:text-telu-red-dark select-none">
                                            <span>{{ $collaboration->collaboration_count }} publikasi</span>
                                            <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="mt-3 text-left bg-telu-bg-soft rounded-md p-4 border border-telu-border max-w-md ml-auto">
                                            <ul class="space-y-2 text-xs text-telu-body list-disc pl-4">
                                                @foreach ($collaboration->shared_publications ?? [] as $title)
                                                    <li class="leading-relaxed">"{{ $title }}"</li>
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
                                borderWidth: 3,
                                shadow: { enabled: false }
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
