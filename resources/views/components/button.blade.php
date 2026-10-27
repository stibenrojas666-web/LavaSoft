@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])

@php
    $base = 'inline-flex min-h-[44px] items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed';
    $variants = [
        'primary'   => 'bg-primary-600 text-white shadow-sm hover:bg-primary-700 hover:-translate-y-px focus:ring-primary-200',
        'cyan'      => 'bg-cyan-700 text-white shadow-sm hover:bg-cyan-800 hover:-translate-y-px focus:ring-cyan-200',
        'neutral'   => 'bg-slate-600 text-white hover:bg-slate-700 focus:ring-slate-200',
        'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:border-cyan-300 hover:bg-cyan-50 focus:ring-primary-100',
        'danger'    => 'bg-red-700 text-white hover:bg-red-800 focus:ring-red-200',
        'ghost'     => 'text-slate-600 hover:bg-slate-100 focus:ring-primary-100',
    ];
    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif