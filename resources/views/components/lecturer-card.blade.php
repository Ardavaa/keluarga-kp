@props(['lecturer'])


@php
    // Hitung inisial nama dosen (maksimal 2 huruf)
    $initials = collect(explode(' ', $lecturer->name))
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
    {{ $attributes->merge(['class' => 'group card-premium relative block bg-white p-5 overflow-hidden border border-telu-border hover:border-telu-red/50 transition-colors duration-200']) }}
>
    <div class="flex items-start gap-4">
        <!-- Avatar Photo / Initial with Flat Color -->
        <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-xl font-bold text-sm uppercase tracking-wider {{ $avatarBg }}">
            @if ($lecturer->photo)
                <img src="{{ $lecturer->photo }}" alt="{{ $lecturer->name }}" class="absolute inset-0 h-full w-full object-cover rounded-xl" onerror="this.remove()">
            @endif
            <span>{{ $initials }}</span>
        </div>

        <!-- Lecturer Identity -->
        <div class="min-w-0 flex-1">
            <h4 class="font-bold text-sm sm:text-base text-telu-ink group-hover:text-telu-red transition-colors truncate" title="{{ $lecturer->name_with_title ?: $lecturer->name }}">
                {{ $lecturer->name_with_title ?: $lecturer->name }}
            </h4>
            <p class="text-xs font-semibold text-telu-muted/80 mt-0.5">
                {{ $lecturer->academic_rank ?: 'Dosen FIF' }} &middot; Kode: {{ $lecturer->lecturer_code ?: '—' }}
            </p>
            
            <!-- Research Field with Icon -->
            <div class="mt-3 flex items-center gap-2 text-xs text-telu-body">
                <svg class="h-3.5 w-3.5 text-telu-muted/70 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <span class="truncate font-medium text-telu-ink/90" title="{{ $lecturer->field ?: 'Umum' }}">
                    {{ $lecturer->field ?: 'Umum' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="mt-5 pt-3 border-t border-telu-border/10 flex items-center justify-between text-xs text-telu-muted">
        <span class="truncate font-medium max-w-[130px]" title="{{ $lecturer->study_program }}">{{ $lecturer->study_program ?: 'FIF Tel-U' }}</span>
        <x-research-group-badge :group="$lecturer->research_group" />
    </div>
</a>
