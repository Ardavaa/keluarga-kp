@props([
    'title' => 'Belum Ada Data',
    'message' => '',
    'icon' => 'warning',
])

@php
    $icons = [
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'search' => 'M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z',
    ];
    $path = $icons[$icon] ?? $icons['warning'];
@endphp

<div {{ $attributes->merge(['class' => 'card-premium p-12 text-center']) }}>
    <svg class="mx-auto h-10 w-10 text-telu-muted/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}" />
    </svg>
    <p class="mt-4 text-base font-semibold text-telu-ink">{{ $title }}</p>
    @if ($message)
        <p class="mt-1 text-sm text-telu-muted">{{ $message }}</p>
    @endif
</div>
