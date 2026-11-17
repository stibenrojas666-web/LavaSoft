
@props([
    'label',
    'value',
    'change' => null,
    'trend' => 'up',
    'icon' => 'chart',
    'tone' => 'cyan',
    'hint' => null,
])

@php
    $tones = [
        'cyan' => 'bg-cyan-50 text-cyan-700 ring-cyan-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'violet' => 'bg-violet-50 text-violet-700 ring-violet-100',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
    ];

    $iconClasses = $tones[$tone] ?? $tones['cyan'];
@endphp

<div class="group relative flex min-w-0 items-center gap-4 overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-4 shadow-[0_5px_20px_rgba(11,42,58,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgba(11,42,58,0.09)] sm:p-5">

    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-slate-50 opacity-0 transition duration-300 group-hover:opacity-100"></div>

    <div class="relative grid h-12 w-12 shrink-0 place-items-center rounded-2xl ring-1 {{ $iconClasses }} transition duration-300 group-hover:scale-105">
        <span class="h-6 w-6">
            @include('layouts.partials.icons', ['icon' => $icon])
        </span>
    </div>

    <div class="relative min-w-0 flex-1">

        <p class="break-words text-xs font-bold leading-tight text-slate-500 sm:text-sm">
            {{ $label }}
        </p>

        <p class="mt-1 text-xl font-extrabold tracking-tight text-[#17394d] sm:text-2xl xl:text-3xl [overflow-wrap:anywhere]">
            {{ $value }}
        </p>

        @if ($hint)
            <p class="mt-1 truncate text-xs text-slate-400">
                {{ $hint }}
            </p>
        @endif

        @if ($change)
            <div class="mt-2 flex flex-wrap items-center gap-1.5">

                <span class="inline-flex items-center gap-1 text-xs font-extrabold {{ $trend === 'up' ? 'text-emerald-600' : 'text-rose-600' }}">

                    @if ($trend === 'up')
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                    @else
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    @endif

                    {{ $change }}

                </span>

                <span class="text-[11px] text-slate-400">
                    vs. mes anterior
                </span>

            </div>
        @endif

    </div>

</div>
