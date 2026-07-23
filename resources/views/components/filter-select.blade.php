@props(['name', 'options', 'selected' => null, 'placeholder' => null, 'id' => null])

@php
    // Normalisasi ke pasangan [value, label] — dipertahankan sebagai array
    // (bukan object) supaya urutan tidak diacak JS untuk key mirip angka
    // (mis. tahun publikasi), lihat catatan di Alpine x-data di bawah.
    $normalizedPairs = collect($options)
        ->map(fn ($label, $key) => is_int($key) ? [(string) $label, (string) $label] : [(string) $key, (string) $label])
        ->values()
        ->all();

    $id = $id ?? $name;
@endphp

<div
    x-data="{
        open: false,
        value: @js((string) ($selected ?? '')),
        options: @js($normalizedPairs),
        placeholder: @js($placeholder),
        get label() {
            if (this.value === '') {
                return this.placeholder ?? (this.options[0] ? this.options[0][1] : '');
            }
            const found = this.options.find((o) => o[0] === this.value);
            return found ? found[1] : this.value;
        },
    }"
    @click.outside="open = false"
    @keydown.escape="open = false"
    class="relative"
>
    <input type="hidden" name="{{ $name }}" :value="value">

    <button
        type="button"
        id="{{ $id }}"
        @click="open = !open"
        class="flex w-full items-center justify-between gap-2 rounded-md border bg-white py-2.5 pl-3.5 pr-3 text-left text-sm text-telu-ink transition-colors focus:outline-none"
        :class="open ? 'border-telu-red ring-1 ring-telu-red' : 'border-telu-border hover:border-telu-red/40'"
    >
        <span class="truncate" x-text="label"></span>
        <svg
            class="h-4 w-4 shrink-0 text-telu-muted transition-transform duration-200"
            :class="open && 'rotate-180'"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="absolute z-20 mt-1.5 w-full overflow-hidden rounded-md border border-telu-border bg-white py-1 shadow-lg shadow-telu-navy/5"
    >
        <ul class="max-h-64 overflow-y-auto text-sm" role="listbox">
            @if ($placeholder)
                <li
                    @click="value = ''; open = false"
                    :class="value === '' ? 'bg-telu-red/10 font-medium text-telu-red' : 'text-telu-ink hover:bg-telu-bg-soft'"
                    class="cursor-pointer px-3.5 py-2 transition-colors"
                    role="option"
                >{{ $placeholder }}</li>
            @endif

            <template x-for="opt in options" :key="opt[0]">
                <li
                    @click="value = opt[0]; open = false"
                    :class="value === opt[0] ? 'bg-telu-red/10 font-medium text-telu-red' : 'text-telu-ink hover:bg-telu-bg-soft'"
                    class="cursor-pointer px-3.5 py-2 transition-colors"
                    x-text="opt[1]"
                    role="option"
                ></li>
            </template>
        </ul>
    </div>
</div>
