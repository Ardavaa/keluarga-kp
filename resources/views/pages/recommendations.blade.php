@extends('layouts.app')

@section('title', 'Rekomendasi Kolaborasi')

@section('content')
    <x-page-header
        title="Rekomendasi Kolaborasi"
        subtitle="Rekomendasi pasangan dosen potensial untuk kolaborasi riset kecerdasan buatan"
    />

    @if ($totalRecommendations === 0)
        <x-empty-state title="Belum Ada Rekomendasi" message="Belum ada hasil perhitungan rekomendasi kolaborasi antar dosen saat ini." />
    @else
        <!-- Top Matches Cards Grid -->
        <div class="mb-10">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-telu-muted">Kecocokan Teratas</h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($topRecommendations as $rec)
                    <div class="card-premium flex flex-col p-5 hover:border-telu-red/50">
                        <!-- Header with score -->
                        <div class="flex items-center justify-between border-b border-telu-border pb-3">
                            <span class="text-xs font-semibold uppercase tracking-wide text-telu-muted">Skor Kecocokan</span>
                            <span class="text-lg font-semibold text-telu-red">{{ number_format($rec->score, 2) }}</span>
                        </div>

                        <!-- Pair -->
                        <div class="mt-3 text-sm">
                            <a href="{{ route('lecturers.show', $rec->lecturer) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                {{ $rec->lecturer->full_name }}
                            </a>
                            <span class="mx-1 text-telu-muted">&amp;</span>
                            <a href="{{ route('lecturers.show', $rec->recommendedLecturer) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                {{ $rec->recommendedLecturer->full_name }}
                            </a>
                        </div>

                        <!-- Reasons -->
                        @if (! empty($rec->reasons))
                            <div class="mt-4 pt-3 border-t border-telu-border">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-telu-muted">Alasan</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($rec->reasons as $reason)
                                        <span class="inline-flex items-center rounded bg-telu-bg-soft-2 px-2 py-0.5 text-xs text-telu-body">
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
            <div class="card-premium p-6">
                <div class="w-full border-b border-telu-border pb-3 flex items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-telu-muted">Rekomendasi Alternatif Lainnya</h2>
                    <span class="text-xs text-telu-muted">{{ $otherRecommendations->count() }} pasangan</span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-telu-border text-left text-xs font-semibold uppercase tracking-wide text-telu-muted">
                                <th class="px-4 py-3">Dosen</th>
                                <th class="px-4 py-3">Rekomendasi Partner</th>
                                <th class="px-4 py-3 text-right">Skor</th>
                                <th class="px-4 py-3">Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-telu-border">
                            @foreach ($otherRecommendations as $rec)
                                <tr class="hover:bg-telu-bg-soft">
                                    <!-- Lecturer 1 -->
                                    <td class="px-4 py-4 align-middle">
                                        <a href="{{ route('lecturers.show', $rec->lecturer) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                            {{ $rec->lecturer->full_name }}
                                        </a>
                                    </td>

                                    <!-- Recommended Lecturer -->
                                    <td class="px-4 py-4 align-middle">
                                        <a href="{{ route('lecturers.show', $rec->recommendedLecturer) }}" class="font-medium text-telu-ink hover:text-telu-red">
                                            {{ $rec->recommendedLecturer->full_name }}
                                        </a>
                                        <span class="block text-xs text-telu-muted">KK: {{ $rec->recommendedLecturer->research_group }}</span>
                                    </td>

                                    <!-- Score -->
                                    <td class="px-4 py-4 text-right align-middle font-semibold text-telu-red">
                                        {{ number_format($rec->score, 2) }}
                                    </td>

                                    <!-- Reasons -->
                                    <td class="px-4 py-4 align-middle">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($rec->reasons ?? [] as $reason)
                                                <span class="inline-flex items-center rounded bg-telu-bg-soft-2 px-2 py-0.5 text-xs text-telu-body">
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
