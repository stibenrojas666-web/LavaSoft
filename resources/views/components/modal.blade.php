
@props([
    'name',
    'title' => null,
    'maxWidth' => 'md',
])

@php
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
    ];

    $width = $widths[$maxWidth] ?? $widths['md'];
@endphp

<div
    x-data="{ show: false }"
    x-show="show"
    x-cloak
    @open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    @close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title-{{ $name }}"
>
    <div class="flex min-h-screen items-center justify-center p-4">

        <div
            x-show="show"
            x-transition.opacity
            @click="show = false"
            class="fixed inset-0 bg-[#071c2b]/60 backdrop-blur-sm"
            aria-hidden="true"
        ></div>

        <div
            x-show="show"
            x-transition
            class="relative w-full {{ $width }} overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        >

            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">

                <div class="min-w-0">
                    @if ($title)
                        <h3
                            id="modal-title-{{ $name }}"
                            class="text-lg font-extrabold text-[#17394d]"
                        >
                            {{ $title }}
                        </h3>
                    @endif
                </div>

                <button
                    @click="show = false"
                    type="button"
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Cerrar ventana"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>

            <div class="max-h-[70vh] space-y-4 overflow-y-auto px-5 py-5 sm:px-6">
                {{ $slot }}
            </div>

            @isset ($footer)
                <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 bg-slate-50 px-5 py-4 sm:px-6">
                    {{ $footer }}
                </div>
            @endisset

        </div>

    </div>
</div>
