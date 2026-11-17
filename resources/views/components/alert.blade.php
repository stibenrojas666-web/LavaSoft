
@props(['type' => 'info'])

@php
    $map = [
        'success' => ['bg-emerald-50 text-emerald-800 border-emerald-200', 'text-emerald-600', 'check-circle'],
        'error' => ['bg-rose-50 text-rose-800 border-rose-200', 'text-rose-600', 'alert-circle'],
        'warning' => ['bg-amber-50 text-amber-800 border-amber-200', 'text-amber-600', 'alert-triangle'],
        'info' => ['bg-cyan-50 text-cyan-800 border-cyan-200', 'text-cyan-600', 'info'],
    ];

    [$classes, $iconColor, $icon] = $map[$type] ?? $map['info'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    role="alert"
    {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-2xl border px-4 py-4 text-sm shadow-sm $classes"]) }}
>
    <span class="mt-0.5 h-5 w-5 shrink-0 {{ $iconColor }}">
        @include('layouts.partials.icons', ['icon' => $icon])
    </span>

    <div class="min-w-0 flex-1 leading-relaxed">
        {{ $slot }}
    </div>

    <button
        @click="show = false"
        type="button"
        class="shrink-0 rounded-lg p-1 {{ $iconColor }} transition hover:bg-black/5"
        aria-label="Cerrar alerta"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
