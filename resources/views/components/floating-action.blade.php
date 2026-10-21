@props(['href', 'label', 'icon' => 'plus'])

<a href="{{ $href }}" class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-full bg-cyan-700 px-5 py-3 font-semibold text-white shadow-xl ring-1 ring-white/50 transition hover:-translate-y-0.5 hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-200 sm:bottom-7 sm:right-7" aria-label="{{ $label }}">
    <span class="h-5 w-5">@include('layouts.partials.icons', ['icon' => $icon])</span>
    <span>{{ $label }}</span>
</a>
