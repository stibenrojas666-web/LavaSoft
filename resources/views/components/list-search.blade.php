@props(['action', 'value' => '', 'placeholder' => 'Escribe para buscar', 'filters' => [], 'hidden' => []])

<form action="{{ $action }}" method="GET" class="mb-5 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
    @foreach($hidden as $name => $hiddenValue)
        <input type="hidden" name="{{ $name }}" value="{{ $hiddenValue }}">
    @endforeach
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="flex min-w-0 flex-1 items-center gap-2 sm:max-w-2xl">
            <label for="buscarListado" class="sr-only">Buscar</label>
            <div class="relative min-w-0 flex-1">
                <span class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">@include('layouts.partials.icons', ['icon' => 'search'])</span>
                <input id="buscarListado" type="search" name="buscar" value="{{ $value }}" maxlength="100" placeholder="{{ $placeholder }}"
                    style="min-height:2.25rem" class="w-full rounded-lg border border-slate-300 bg-white py-1.5 pl-9 pr-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100">
            </div>
            <button type="submit" class="inline-flex h-9 items-center justify-center rounded-lg bg-cyan-700 px-3 text-sm font-semibold text-white transition hover:bg-cyan-800">Buscar</button>
        </div>
        <div class="flex items-center gap-2">
            @if($value !== '' || collect($filters)->contains(fn ($filter) => ($filter['value'] ?? '') !== ''))
                <a href="{{ $action }}" class="inline-flex h-9 items-center rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Limpiar</a>
            @endif
            @if(count($filters))
                <details class="relative" @if(collect($filters)->contains(fn ($filter) => ($filter['value'] ?? '') !== '')) open @endif>
                    <summary class="flex h-9 cursor-pointer list-none items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <span>Filtros</span>
                        <span class="text-xs text-slate-400" aria-hidden="true">⌄</span>
                    </summary>
                    <div class="absolute right-0 z-20 mt-2 w-[min(90vw,34rem)] rounded-xl border border-slate-200 bg-white p-3 shadow-xl sm:p-4">
                        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($filters as $filter)
                <div>
                    <label for="filtro-{{ $filter['name'] }}" class="mb-1 block text-xs font-medium text-slate-600">{{ $filter['label'] }}</label>
                    @if(($filter['type'] ?? 'select') === 'date')
                        <input id="filtro-{{ $filter['name'] }}" type="date" name="{{ $filter['name'] }}" value="{{ $filter['value'] ?? '' }}" style="min-height:2.25rem" class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                    @else
                        <select id="filtro-{{ $filter['name'] }}" name="{{ $filter['name'] }}" style="min-height:2.25rem" class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                            @foreach($filter['options'] as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" @selected((string) ($filter['value'] ?? '') === (string) $optionValue)>{{ $optionLabel }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endforeach
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="inline-flex h-8 items-center rounded-lg bg-cyan-700 px-3 text-xs font-semibold text-white hover:bg-cyan-800">Aplicar filtros</button>
                        </div>
                    </div>
                </details>
            @endif
        </div>
    </div>
    @error('buscar')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    @if($value !== '')<p class="mt-2 text-xs text-slate-500">Resultados para: <span class="font-semibold text-slate-700">{{ $value }}</span></p>@endif
</form>
