
@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_28px_rgba(11,42,58,0.05)] transition-shadow duration-200 hover:shadow-[0_12px_32px_rgba(11,42,58,0.08)]']) }}>

    @if ($title)
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">

            <div class="min-w-0">
                <h3 class="text-base font-extrabold tracking-tight text-[#17394d]">
                    {{ $title }}
                </h3>

                @if ($subtitle)
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            @isset($actions)
                <div class="flex flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset

        </div>
    @endif

    <div class="{{ $padding ? 'p-5 sm:p-6' : '' }}">
        {{ $slot }}
    </div>

</div>
