
@props([
    'action',
    'value' => '',
    'placeholder' => 'Escribe para buscar',
    'filters' => [],
    'hidden' => [],
])

<form
    action="{{ $action }}"
    method="GET"
    novalidate
    class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
>
    @foreach ($hidden as $name => $hiddenValue)
        <input type="hidden" name="{{ $name }}" value="{{ $hiddenValue }}">
    @endforeach

    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex min-w-0 flex-1 items-center gap-2">

            <div class="relative min-w-0 flex-1">
                <label for="buscarListado" class="sr-only">Buscar registros</label>

                <span class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">
                    @include('layouts.partials.icons', ['icon' => 'search'])
                </span>

                <input
                    id="buscarListado"
                    type="search"
                    name="buscar"
                    value="{{ $value }}"
                    placeholder="{{ $placeholder }}"
                    class="min-h-[44px] w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-sm text-slate-800 transition focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cyan-100"
                >
            </div>

            <button
                type="submit"
                class="inline-flex min-h-[44px] shrink-0 items-center justify-center gap-2 rounded-xl bg-[#0b3045] px-4 text-sm font-bold text-white transition hover:bg-[#12465f]"
            >
                <span class="h-4 w-4">
                    @include('layouts.partials.icons', ['icon' => 'search'])
                </span>
                <span>Buscar</span>
            </button>

        </div>

        <div class="flex flex-wrap items-center gap-2">

            @if ($value !== '' || collect($filters)->contains(fn ($filter) => ($filter['value'] ?? '') !== ''))
                <a
                    href="{{ $action }}"
                    class="inline-flex min-h-[40px] items-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Limpiar
                </a>
            @endif

            @if (count($filters))
                <details class="relative" data-dismiss-on-outside>
                    <summary class="flex min-h-[40px] cursor-pointer list-none items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-800" aria-label="Mostrar u ocultar filtros">
                        <span class="h-4 w-4 text-cyan-700">
                            @include('layouts.partials.icons', ['icon' => 'filter'])
                        </span>
                        Filtros
                        @php($filtrosActivos = collect($filters)->filter(fn ($filter) => ($filter['value'] ?? '') !== '')->count())
                        @if($filtrosActivos)
                            <span class="rounded-full bg-cyan-100 px-2 py-0.5 text-[10px] font-bold text-cyan-800">{{ $filtrosActivos }}</span>
                        @endif
                        <span aria-hidden="true">⌄</span>
                    </summary>

                    <div class="lava-filter-popover absolute right-0 top-full z-40 mt-2 w-[min(28rem,calc(100vw-2rem))] rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl">

                        <div class="mb-4">
                            <h3 class="text-sm font-extrabold text-[#17394d]">Filtrar registros</h3>
                            <p class="mt-1 text-xs text-slate-400">Selecciona los criterios que deseas aplicar.</p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">

                            @foreach ($filters as $filter)
                                <div class="min-w-0">

                                    <label
                                        for="filtro-{{ $filter['name'] }}"
                                        class="mb-1.5 block text-xs font-bold text-slate-600"
                                    >
                                        {{ $filter['label'] }}
                                    </label>

                                    @if (($filter['type'] ?? 'select') === 'date')
                                        <input
                                            id="filtro-{{ $filter['name'] }}"
                                            type="date"
                                            name="{{ $filter['name'] }}"
                                            value="{{ $filter['value'] ?? '' }}"
                                            class="min-h-[36px] w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                                        >
                                    @else
                                        <select
                                            id="filtro-{{ $filter['name'] }}"
                                            name="{{ $filter['name'] }}"
                                            class="min-h-[36px] w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                                        >
                                            @foreach ($filter['options'] as $optionValue => $optionLabel)
                                                <option
                                                    value="{{ $optionValue }}"
                                                    @selected((string) ($filter['value'] ?? '') === (string) $optionValue)
                                                >
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @error($filter['name'])
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror

                                </div>
                            @endforeach

                        </div>

                        <div class="mt-5 flex justify-end">
                            <button
                                type="submit"
                                class="min-h-[36px] rounded-xl bg-cyan-600 px-4 text-sm font-bold text-white transition hover:bg-cyan-700"
                            >
                                Aplicar filtros
                            </button>
                        </div>

                    </div>
                </details>
            @endif

        </div>

    </div>

    @if ($value !== '')
        <p class="mt-3 text-xs text-slate-500">
            Resultados para:
            <span class="font-bold text-slate-700">{{ $value }}</span>
        </p>
    @endif

</form>
