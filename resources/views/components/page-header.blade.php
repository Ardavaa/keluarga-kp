@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    <h1 class="text-2xl font-semibold text-telu-ink">{{ $title }}</h1>

    @if ($subtitle)
        <p class="mt-1 text-sm text-telu-muted">{{ $subtitle }}</p>
    @endif

    <div class="mt-4 h-1 w-16 rounded-full bg-telu-red"></div>
</div>
