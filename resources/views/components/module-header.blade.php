@props([
    'title',
    'description' => null,
    'icon' => 'grid',
    'backUrl' => null,
    'backLabel' => 'Volver',
    'variant' => 'form',
])

@if($variant === 'list')
    <header class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $title }}</h1>
            @if($description)
                <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
            @endif
        </div>
        @isset($actions)
            <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
        @endisset
    </header>
@else
<header class="modulo-banner lavasoft-module-header mb-5 flex flex-wrap items-center justify-between gap-4 px-5 py-6 sm:px-8 sm:py-7">
    <div class="relative z-10 flex min-w-0 items-center gap-4">
        <span class="modulo-icon grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-cyan-100 p-3 text-cyan-900">
            @include('layouts.partials.icons', ['icon' => $icon])
        </span>
        <div class="min-w-0">
            <h1 class="text-xl font-extrabold text-white sm:text-2xl">{{ $title }}</h1>
            @if($description)
                <p class="mt-1 text-sm text-cyan-50">{{ $description }}</p>
            @endif
        </div>
    </div>

    @if($backUrl)
        <a href="{{ $backUrl }}" class="relative z-10 inline-flex min-h-10 items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
            <span aria-hidden="true">←</span> {{ $backLabel }}
        </a>
    @endif
</header>
@endif
