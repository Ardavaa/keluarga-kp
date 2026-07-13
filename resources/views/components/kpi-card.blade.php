@props(['label', 'value', 'icon' => null, 'type' => 'red'])

@php
    $iconColorClasses = [
        'red' => 'bg-telu-red/10 text-telu-red',
        'navy' => 'bg-telu-navy/10 text-telu-navy',
        'green' => 'bg-rg-dsis/10 text-rg-dsis',
        'blue' => 'bg-rg-seal/10 text-rg-seal',
    ][$type] ?? 'bg-telu-red/10 text-telu-red';
@endphp

<div {{ $attributes->merge(['class' => 'card-premium p-5']) }}>
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-telu-muted">
                {{ $label }}
            </p>
            <p class="mt-1 text-3xl font-semibold text-telu-ink">
                {{ $value }}
            </p>
        </div>

        @if ($icon)
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md {{ $iconColorClasses }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
