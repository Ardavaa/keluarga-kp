@extends('layouts.app')

@section('title', 'Rekomendasi Kolaborasi')

@section('content')
    <x-page-header
        title="Rekomendasi Kolaborasi"
        subtitle="Rekomendasi pasangan dosen potensial untuk kolaborasi riset kecerdasan buatan"
    />

    @if ($totalRecommendations === 0)
        <div class="rounded-2xl border border-telu-border/30 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-telu-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="mt-4 text-base font-semibold text-telu-ink">Belum Ada Rekomendasi</p>
            <p class="mt-1 text-sm text-telu-muted">Belum ada hasil perhitungan rekomendasi kolaborasi antar dosen saat ini.</p>
        </div>
    @else
        <!-- Top Matches Cards Grid -->
        <div class="mb-10">
            <h3 class="text-xs font-bold uppercase tracking-wider text-telu-muted/80 mb-4">Kecocokan Teratas (Top Matches)</h3>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ($topRecommendations as $rec)
                    @php
                        $init1 = collect(explode(' ', $rec->lecturer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                        $init2 = collect(explode(' ', $rec->recommendedLecturer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                    @endphp
                    <div class="group card-premium relative flex flex-col justify-between bg-white p-6 border border-telu-border hover:border-telu-red/50 overflow-hidden h-full">
                        <!-- Red top accent bar -->
                        <div class="absolute left-0 right-0 top-0 h-1 bg-telu-red"></div>
                        
                        <div>
                            <!-- Header with score -->
                            <div class="flex items-center justify-between pb-4 border-b border-telu-border/10">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-telu-muted/80">Skor Kecocokan</span>
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-telu-red/10 border border-telu-red/20 text-telu-red font-extrabold text-xs">
                                    {{ number_format($rec->score, 2) }}
                                </div>
                            </div>

                            <!-- Visual Connector Card -->
                            <div class="bg-telu-bg-soft rounded-2xl p-4 border border-telu-border/10 mt-5 flex items-center justify-between gap-3 shadow-inner">
                                <!-- Lecturer 1 (Left) -->
                                <div class="flex flex-col items-center text-center min-w-0 flex-1">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-telu-navy text-white text-[10px] font-bold uppercase tracking-wider shadow-sm mb-2">
                                        {{ $init1 }}
                                    </div>
                                    <a href="{{ route('lecturers.show', $rec->lecturer) }}" class="font-bold text-xs text-telu-ink hover:text-telu-red hover:underline line-clamp-1 w-full" title="{{ $rec->lecturer->name }}">
                                        {{ $rec->lecturer->name }}
                                    </a>
                                </div>

                                <!-- Arrow Connection (Middle) -->
                                <div class="shrink-0 flex h-7 w-7 items-center justify-center rounded-full bg-telu-red/10 text-telu-red">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>

                                <!-- Lecturer 2 (Right) -->
                                <div class="flex flex-col items-center text-center min-w-0 flex-1">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-telu-red text-white text-[10px] font-bold uppercase tracking-wider shadow-sm mb-2">
                                        {{ $init2 }}
                                    </div>
                                    <a href="{{ route('lecturers.show', $rec->recommendedLecturer) }}" class="font-bold text-xs text-telu-ink hover:text-telu-red hover:underline line-clamp-1 w-full" title="{{ $rec->recommendedLecturer->name }}">
                                        {{ $rec->recommendedLecturer->name }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Reasons list as dynamic Tag Pills -->
                        @if (! empty($rec->reasons))
                            <div class="mt-5 pt-4 border-t border-telu-border/10">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-telu-muted mb-2">Alasan Rekomendasi</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($rec->reasons as $reason)
                                        <span class="inline-flex items-center rounded-lg bg-telu-bg-soft/75 px-2.5 py-1 text-xs font-semibold text-telu-body border border-telu-border/20 hover:bg-white hover:border-telu-red/20 transition-all select-none">
                                            {{ $reason }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Other Recommendations Table Card -->
        @if ($otherRecommendations->isNotEmpty())
            <div class="card-premium bg-white p-6 md:p-8 shadow-sm border border-telu-border/30">
                <div class="w-full border-b border-telu-border/10 pb-4 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-telu-ink">Rekomendasi Alternatif Lainnya</h3>
                        <p class="text-xs text-telu-muted">Daftar pasangan dosen yang berpotensi kolaboratif dengan kecocokan menengah</p>
                    </div>
                    <span class="inline-flex rounded-xl bg-telu-navy/10 px-2.5 py-1 text-xs font-bold text-telu-navy">
                        {{ $otherRecommendations->count() }} Pasangan
                    </span>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-telu-border/20 text-left text-xs font-bold uppercase tracking-wider text-telu-muted bg-telu-bg-soft/40">
                                <th class="px-4 py-3 font-semibold rounded-l-xl">Dosen</th>
                                <th class="px-4 py-3 font-semibold">Rekomendasi Partner</th>
                                <th class="px-4 py-3 text-right font-semibold">Skor</th>
                                <th class="px-4 py-3 font-semibold rounded-r-xl">Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-telu-border/10">
                            @foreach ($otherRecommendations as $rec)
                                @php
                                    $init1 = collect(explode(' ', $rec->lecturer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                                    $init2 = collect(explode(' ', $rec->recommendedLecturer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                                @endphp
                                <tr class="hover:bg-telu-bg-soft/20 transition-colors">
                                    <!-- Lecturer 1 -->
                                    <td class="px-4 py-4 align-middle">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-telu-navy text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                {{ $init1 }}
                                            </div>
                                            <a href="{{ route('lecturers.show', $rec->lecturer) }}" class="font-bold text-telu-ink hover:text-telu-red hover:underline">
                                                {{ $rec->lecturer->name }}
                                            </a>
                                        </div>
                                    </td>
                                    
                                    <!-- Recommended Lecturer -->
                                    <td class="px-4 py-4 align-middle">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-telu-red text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                {{ $init2 }}
                                            </div>
                                            <div class="flex flex-col">
                                                <a href="{{ route('lecturers.show', $rec->recommendedLecturer) }}" class="font-bold text-telu-ink hover:text-telu-red hover:underline">
                                                    {{ $rec->recommendedLecturer->name }}
                                                </a>
                                                <span class="text-[9px] font-bold text-telu-muted">KK: {{ $rec->recommendedLecturer->research_group }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Score -->
                                    <td class="px-4 py-4 text-right align-middle font-bold text-telu-red">
                                        <span class="inline-flex items-center rounded-lg bg-telu-red/10 px-2.5 py-1 text-xs font-extrabold text-telu-red border border-telu-red/20 shadow-sm">
                                            {{ number_format($rec->score, 2) }}
                                        </span>
                                    </td>

                                    <!-- Reasons -->
                                    <td class="px-4 py-4 align-middle">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($rec->reasons ?? [] as $reason)
                                                <span class="inline-flex items-center rounded-md bg-telu-bg-soft px-2 py-0.5 text-[10px] font-medium text-telu-body border border-telu-border/20">
                                                    {{ $reason }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
@endsection
