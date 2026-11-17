
@props([
    'title',
    'description' => null,
    'icon' => 'grid',
    'variant' => 'form',
])

@if ($variant === 'list')

    <header class="mb-6 flex flex-wrap items-center justify-between gap-4">

        <div class="min-w-0">
            <h1 class="text-2xl font-extrabold tracking-tight text-[#17394d] sm:text-3xl">
                {{ $title }}
            </h1>

            @if ($description)
                <p class="mt-1.5 text-sm leading-relaxed text-slate-500">
                    {{ $description }}
                </p>
            @endif
        </div>

        @isset ($actions)
            <div class="flex flex-wrap items-center gap-2">
                {{ $actions }}
            </div>
        @endisset

    </header>

@else

    <header class="modulo-banner lavasoft-module-header relative mb-6 flex flex-wrap items-center justify-between gap-5 overflow-hidden rounded-2xl px-5 py-6 shadow-lg shadow-[#0b3045]/10 sm:px-8 sm:py-7">

        <div class="relative z-10 flex min-w-0 items-center gap-4">

            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl border border-white/20 bg-white/15 p-3 text-white shadow-inner backdrop-blur-sm">
                @include('layouts.partials.icons', ['icon' => $icon])
            </span>

            <div class="min-w-0">
                <h1 class="text-xl font-extrabold tracking-tight text-white sm:text-2xl">
                    {{ $title }}
                </h1>

                @if ($description)
                    <p class="mt-1.5 text-sm leading-relaxed text-cyan-50/90">
                        {{ $description }}
                    </p>
                @endif
            </div>

        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2">

            @isset ($actions)
                {{ $actions }}
            @endisset

        </div>

    </header>

@endif
