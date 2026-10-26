{{--
    Uso:
    <x-card title="Título" subtitle="Opcional">
        Contenido...
    </x-card>
--}}
@props(['title' => null, 'subtitle' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-slate-200/70 shadow-[0_10px_30px_rgba(11,42,58,0.06)]']) }}>
    @if ($title)
        <div class="px-5 sm:px-7 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-800">{{ $title }}</h3>
                @if ($subtitle)
                    <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="{{ $padding ? 'p-5 sm:p-7' : '' }}">
        {{ $slot }}
    </div>
</div>