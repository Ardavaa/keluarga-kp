@props(['excelRoute' => null, 'pdfRoute' => null, 'query' => []])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @if ($excelRoute)
        <a
            href="{{ route($excelRoute, $query) }}"
            class="inline-flex items-center gap-2 rounded-md border border-telu-border bg-white px-3 py-1.5 text-xs font-medium text-telu-body hover:border-rg-dsis/50 hover:text-rg-dsis"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel
        </a>
    @endif

    @if ($pdfRoute)
        <a
            href="{{ route($pdfRoute, $query) }}"
            class="inline-flex items-center gap-2 rounded-md border border-telu-border bg-white px-3 py-1.5 text-xs font-medium text-telu-body hover:border-telu-red/50 hover:text-telu-red"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            PDF
        </a>
    @endif
</div>
