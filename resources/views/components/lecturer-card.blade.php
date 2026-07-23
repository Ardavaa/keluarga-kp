@props(['lecturer'])


@php
    // Hitung inisial nama dosen (maksimal 2 huruf)
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

<a
    href="{{ route('lecturers.show', $lecturer) }}"
    {{ $attributes->merge(['class' => 'group card-premium block p-5 hover:border-telu-red/50']) }}
>
    <div class="flex items-start gap-4">
        <!-- Avatar Photo / Initial -->
        <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-[0.625rem] font-semibold text-sm uppercase tracking-wide overflow-hidden {{ $avatarBg }}">
            @if ($lecturer->photo)
                <img src="{{ $lecturer->photo }}" alt="{{ $lecturer->full_name }}" class="absolute inset-0 h-full w-full object-cover" onerror="this.remove()">
            @endif
            <span>{{ $initials }}</span>
        </div>

        <!-- Lecturer Identity -->
        <div class="min-w-0 flex-1">
            <h4 class="font-semibold text-sm sm:text-base text-telu-ink group-hover:text-telu-red truncate" title="{{ $lecturer->name_with_title ?: $lecturer->full_name }}">
                {{ $lecturer->name_with_title ?: $lecturer->full_name }}
            </h4>
            <p class="text-xs text-telu-muted mt-0.5">
                {{ $lecturer->academic_rank ?: 'Dosen FIF' }} &middot; Kode: {{ $lecturer->lecturer_code ?: '—' }}
            </p>

            <div class="mt-3 text-xs text-telu-body">
                <span class="truncate text-telu-ink" title="{{ $lecturer->field ?: 'Umum' }}">
                    {{ $lecturer->field ?: 'Umum' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="mt-5 pt-3 border-t border-telu-border flex items-center justify-between text-xs text-telu-muted">
        <span class="truncate max-w-[130px]" title="{{ $lecturer->study_program }}">{{ $lecturer->study_program ?: 'FIF Tel-U' }}</span>
        <x-research-group-badge :group="$lecturer->research_group" />
    </div>
</a>
