@if ($paginator->hasPages())
    @php
        $botonBase = 'inline-flex min-h-10 min-w-10 items-center justify-center rounded-xl border px-3 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2';
        $esPaginadorCompleto = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    @endphp

    <nav role="navigation" aria-label="Paginación de resultados"
        class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-center text-sm text-slate-500 sm:text-left">
            @if ($paginator->count())
                Mostrando
                <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}{{ $paginator->lastItem() }}</span>
                @if ($esPaginadorCompleto)
                    de <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
                @endif
                registros
            @else
                No hay registros para mostrar
            @endif
        </p>

        <div class="flex flex-wrap items-center justify-center gap-1.5">
            {{-- Botón anterior --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="{{ $botonBase }} cursor-not-allowed border-slate-200 bg-slate-50 text-slate-300">
                    <svg class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 15.71a1 1 0 01-1.42 0l-5-5a1 1 0 010-1.42l5-5a1 1 0 111.42 1.42L8.5 10l4.29 4.29a1 1 0 010 1.42z" clip-rule="evenodd" />
                    </svg>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior"
                    class="{{ $botonBase }} border-slate-200 bg-white text-slate-600 hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800">
                    <svg class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 15.71a1 1 0 01-1.42 0l-5-5a1 1 0 010-1.42l5-5a1 1 0 111.42 1.42L8.5 10l4.29 4.29a1 1 0 010 1.42z" clip-rule="evenodd" />
                    </svg>
                    Anterior
                </a>
            @endif

            {{-- Números de página --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-hidden="true" class="px-1.5 text-sm font-semibold text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" aria-label="Página {{ $page }}"
                                class="{{ $botonBase }} border-cyan-600 bg-cyan-600 text-white shadow-sm shadow-cyan-900/10">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="Ir a la página {{ $page }}"
                                class="{{ $botonBase }} border-slate-200 bg-white text-slate-600 hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botón siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente"
                    class="{{ $botonBase }} border-slate-200 bg-white text-slate-600 hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800">
                    Siguiente
                    <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 4.29a1 1 0 011.42 0l5 5a1 1 0 010 1.42l-5 5a1 1 0 11-1.42-1.42L11.5 10 7.21 5.71a1 1 0 010-1.42z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" class="{{ $botonBase }} cursor-not-allowed border-slate-200 bg-slate-50 text-slate-300">
                    Siguiente
                    <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 4.29a1 1 0 011.42 0l5 5a1 1 0 010 1.42l-5 5a1 1 0 11-1.42-1.42L11.5 10 7.21 5.71a1 1 0 010-1.42z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
