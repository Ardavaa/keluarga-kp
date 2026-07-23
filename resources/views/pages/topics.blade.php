@extends('layouts.app')

@section('title', 'Topik Dominan')

@section('content')
    <x-page-header
        title="Topik Dominan"
        subtitle="Analisis dan visualisasi topik riset AI yang paling banyak ditekuni oleh Dosen FIF"
    />

    @if ($labels->isEmpty())
        <x-empty-state title="Data Tidak Tersedia" message="Belum ada data kategori bidang kecerdasan buatan dari dosen FIF saat ini." />
    @else
        @php
            // Palet warna yang serasi dengan chart
            $colors = ['#9F1521', '#002147', '#6BCB77', '#4D96FF', '#FFB319', '#C51626', '#8F131E'];
        @endphp

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">
            <!-- Left Column: Visual Doughnut Chart -->
            <div class="card-premium flex flex-col items-center p-6 lg:col-span-5">
                <div class="w-full border-b border-telu-border pb-3 mb-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Visualisasi Distribusi</h2>
                </div>

                <div class="relative w-full max-w-[280px] aspect-square flex items-center justify-center">
                    <canvas id="ai-categories-chart"></canvas>
                </div>
            </div>

            <!-- Right Column: List & Details Table -->
            <div class="card-premium p-6 lg:col-span-7">
                <div class="w-full border-b border-telu-border pb-3 mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Sebaran Bidang Keahlian AI</h2>
                    <span class="text-xs text-telu-muted">{{ $labels->count() }} topik &middot; dari {{ $totalLecturers }} dosen</span>
                </div>

                <div class="space-y-5">
                    @foreach ($labels as $index => $label)
                        @php
                            $color = $colors[$index % count($colors)];
                            $percentage = ($totalLecturers > 0) ? ($counts[$index] / $totalLecturers) * 100 : 0;
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-sm gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $color }};" aria-hidden="true"></span>
                                    <span class="text-telu-ink truncate" title="{{ $label }}">{{ $label }}</span>
                                </div>
                                <div class="flex items-center gap-3 shrink-0 text-telu-muted">
                                    <span>{{ $counts[$index] }} dosen</span>
                                    <span class="text-xs tabular-nums">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>

                            <div class="h-1.5 w-full rounded-full bg-telu-bg-soft-2 overflow-hidden">
                                <div class="h-full rounded-full" style="background-color: {{ $color }}; width: {{ $percentage }}%;"></div>
                            </div>
                        </div>
                    @endforeach
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
