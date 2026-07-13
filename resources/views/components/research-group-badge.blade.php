@props(['group'])

@php
    $normalized = strtoupper(trim((string) $group));

    $styles = match ($normalized) {
        'CITI' => 'bg-rg-citi/12 text-rg-citi ring-rg-citi/20',
        'DSIS' => 'bg-rg-dsis/12 text-rg-dsis ring-rg-dsis/20',
        'SEAL' => 'bg-rg-seal/12 text-rg-seal ring-rg-seal/20',
        default => 'bg-rg-unknown/12 text-rg-unknown ring-rg-unknown/20',
    };

    $label = $normalized !== '' ? $normalized : 'UNKNOWN';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset $styles"]) }}>
    {{ $label }}
</span>
