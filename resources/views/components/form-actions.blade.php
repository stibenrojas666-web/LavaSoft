@props([
    'cancelUrl',
    'saveLabel' => 'Guardar cambios',
    'cancelLabel' => 'Cancelar',
])

<div {{ $attributes->merge(['class' => 'flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/80 px-5 py-4 sm:flex-row sm:px-7']) }}>
    <a href="{{ $cancelUrl }}" class="inline-flex min-h-[46px] items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800">
        <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'arrow-left'])</span>
        {{ $cancelLabel }}
    </a>
    <button type="submit" class="inline-flex min-h-[46px] items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#087b98] to-[#06364c] px-6 text-sm font-bold text-white shadow-md shadow-cyan-900/15 transition hover:-translate-y-0.5 hover:brightness-110 focus:outline-none focus:ring-4 focus:ring-cyan-200">
        <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'save'])</span>
        {{ $saveLabel }}
        <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'arrow-right'])</span>
    </button>
</div>
