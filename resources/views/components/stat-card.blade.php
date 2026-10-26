{{--
    Uso:
    <x-stat-card
        label="Total de usuarios"
        :value="$totalUsers"
        change="+12.4%"
        trend="up"
        icon="users"
    />
    trend: "up" | "down"
--}}
@props(['label', 'value', 'change' => null, 'trend' => 'up', 'icon' => 'chart', 'tone' => 'cyan', 'hint' => null])

@php
    $tones = [
        'cyan'    => 'bg-cyan-100 text-cyan-800',
        'emerald' => 'bg-emerald-100 text-emerald-800',
        'amber'   => 'bg-amber-100 text-amber-800',
        'violet'  => 'bg-violet-100 text-violet-800',
        'rose'    => 'bg-rose-100 text-rose-800',
    ];
    $iconClasses = $tones[$tone] ?? $tones['cyan'];
@endphp

<div class="group rounded-3xl border border-slate-200/70 bg-white p-5 shadow-[0_10px_30px_rgba(11,42,58,0.06)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_16px_36px_rgba(11,42,58,0.10)] sm:p-6">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
            <p class="mt-2 truncate text-3xl font-extrabold tracking-tight text-slate-800">{{ $value }}</p>
        </div>
        <div class="grid h-12 w-12 flex-shrink-0 place-items-center rounded-2xl {{ $iconClasses }} transition group-hover:scale-105">
            <span class="block h-6 w-6">@include('layouts.partials.icons', ['icon' => $icon])</span>
        </div>
    </div>

    @if ($hint)
        <p class="mt-4 text-sm text-slate-500">{{ $hint }}</p>
    @endif

    @if ($change)
        <div class="mt-4 flex items-center gap-1.5 text-sm">
            <span class="inline-flex items-center gap-1 font-semibold {{ $trend === 'up' ? 'text-emerald-700' : 'text-red-700' }}">
                @if ($trend === 'up')
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                @else
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                @endif
                {{ $change }}
            </span>
            <span class="text-slate-500">vs. mes anterior</span>
        </div>
    @endif
</div>