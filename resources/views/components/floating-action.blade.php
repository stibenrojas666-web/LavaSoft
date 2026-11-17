
@props([
    'href',
    'label',
    'icon' => 'plus',
])

<a
    href="{{ $href }}"
    aria-label="{{ $label }}"
    class="fixed bottom-6 right-5 z-30 inline-flex min-h-[52px] items-center gap-2.5 rounded-2xl bg-gradient-to-r from-[#0b3045] to-cyan-700 px-5 py-3 text-sm font-extrabold text-white shadow-xl shadow-cyan-900/20 ring-1 ring-white/20 transition-all duration-200 hover:-translate-y-1 hover:shadow-2xl hover:shadow-cyan-900/25 focus:outline-none focus:ring-4 focus:ring-cyan-200 sm:bottom-8 sm:right-8"
>
    <span class="h-5 w-5 shrink-0">
        @include('layouts.partials.icons', ['icon' => $icon])
    </span>

    <span>{{ $label }}</span>
</a>
