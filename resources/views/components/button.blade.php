
@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex min-h-[42px] items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition-all duration-200 focus:outline-none focus:ring-4 disabled:pointer-events-none disabled:opacity-50';

    $variants = [
        'primary' => 'bg-[#0b3045] text-white shadow-sm hover:bg-[#12465f] hover:-translate-y-0.5 focus:ring-cyan-100',
        'cyan' => 'bg-cyan-600 text-white shadow-sm hover:bg-cyan-700 hover:-translate-y-0.5 focus:ring-cyan-100',
        'neutral' => 'bg-slate-700 text-white hover:bg-slate-800 focus:ring-slate-200',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 shadow-sm hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-800 focus:ring-cyan-100',
        'danger' => 'bg-rose-600 text-white shadow-sm hover:bg-rose-700 focus:ring-rose-100',
        'success' => 'bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 focus:ring-emerald-100',
        'ghost' => 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-slate-100',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif
