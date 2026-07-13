@extends('layouts.app')

@section('title', 'Topik Dominan')

@section('content')
    <x-page-header
        title="Topik Dominan"
        subtitle="Analisis dan visualisasi topik riset AI yang paling banyak ditekuni oleh Dosen FIF"
    />

    @if ($labels->isEmpty())
        <div class="rounded-2xl border border-telu-border/30 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-telu-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="mt-4 text-base font-semibold text-telu-ink">Data Tidak Tersedia</p>
            <p class="mt-1 text-sm text-telu-muted">Belum ada data kategori bidang kecerdasan buatan dari dosen FIF saat ini.</p>
        </div>
    @else
        @php
            // Palet warna yang serasi dengan chart
            $colors = ['#9F1521', '#002147', '#6BCB77', '#4D96FF', '#FFB319', '#C51626', '#8F131E'];
        @endphp

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">
            <!-- Left Column: Visual Doughnut Chart -->
            <div class="card-premium flex flex-col items-center justify-center bg-white p-8 lg:col-span-5 shadow-sm">
                <div class="w-full border-b border-telu-border/10 pb-4 mb-6">
                    <h3 class="text-base font-bold text-telu-ink">Visualisasi Distribusi</h3>
                    <p class="text-xs text-telu-muted">Proporsi keahlian dosen berdasarkan kategori AI</p>
                </div>
                
                <div class="relative w-full max-w-[280px] aspect-square flex items-center justify-center">
                    <canvas id="ai-categories-chart"></canvas>
                </div>
            </div>

            <!-- Right Column: List & Details Table -->
            <div class="card-premium bg-white p-8 lg:col-span-7 shadow-sm">
                <div class="w-full border-b border-telu-border/10 pb-4 mb-6 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-telu-ink">Sebaran Bidang Keahlian AI</h3>
                        <p class="text-xs text-telu-muted">Persentase dihitung terhadap total {{ $totalLecturers }} dosen</p>
                    </div>
                    <span class="inline-flex rounded-xl bg-telu-red/10 px-2.5 py-1 text-xs font-bold text-telu-red">
                        {{ $labels->count() }} Topik Ditemukan
                    </span>
                </div>

                <div class="overflow-hidden">
                    <div class="space-y-6">
                        @foreach ($labels as $index => $label)
                            @php
                                $color = $colors[$index % count($colors)];
                                $percentage = ($totalLecturers > 0) ? ($counts[$index] / $totalLecturers) * 100 : 0;
                            @endphp
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm gap-2">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <!-- Sync color dot with chart -->
                                        <span class="h-3 w-3 shrink-0 rounded-full shadow-sm" style="background-color: {{ $color }};" aria-hidden="true"></span>
                                        <span class="font-bold text-telu-ink truncate" title="{{ $label }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0 font-semibold">
                                        <span class="text-telu-ink">{{ $counts[$index] }} Dosen</span>
                                        <span class="text-telu-muted text-xs bg-telu-bg-soft px-1.5 py-0.5 rounded border border-telu-border/40">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Sleek progress bar -->
                                <div class="h-2 w-full rounded-full bg-telu-bg-soft border border-telu-border/20 overflow-hidden">
                                    <div 
                                        class="h-full rounded-full transition-all duration-500 ease-out" 
                                        style="background-color: {{ $color }}; width: {{ $percentage }}%;"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const ctx = document.getElementById('ai-categories-chart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($labels),
                            datasets: [{
                                data: @json($counts),
                                backgroundColor: @json(array_slice($colors, 0, $labels->count())),
                                borderWidth: 2,
                                borderColor: '#ffffff',
                                borderRadius: 5,
                                hoverOffset: 6
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            plugins: {
                                legend: {
                                    display: false // Legenda sudah direpresentasikan dengan indah oleh list tabel kanan
                                },
                                tooltip: {
                                    backgroundColor: '#002147',
                                    titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                                    bodyFont: { family: 'Inter', size: 12 },
                                    padding: 10,
                                    cornerRadius: 8,
                                    displayColors: true
                                }
                            },
                        },
                    });
                });
            </script>
        @endpush
    @endif
@endsection
