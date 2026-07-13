@props(['phase' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-dashed border-telu-border bg-white p-10 text-center']) }}>
    <p class="text-sm font-medium text-telu-ink">Halaman ini sedang dalam pengembangan.</p>

    @if ($phase)
        <p class="mt-1 text-xs text-telu-muted">
            Akan diisi pada Fase {{ $phase }} — lihat <code class="rounded bg-telu-bg-soft-2 px-1 py-0.5">docs/ROADMAP.md</code>
        </p>
    @endif
</div>
