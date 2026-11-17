@extends('layouts.app')

@section('title', 'Precios de servicio')
@section('page-title', 'Precios de servicio')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6">

    <x-module-header variant="list" title="Precios de servicio" description="Administra las tarifas según el servicio y el tipo de vehículo." />

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-cyan-50 text-2xl">💲</div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Precios registrados</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $precioServicio->total() }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-sky-50 text-2xl">🚘</div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Resultados en esta página</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $precioServicio->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <h2 class="font-bold text-slate-800">Listado de tarifas</h2>
            <p class="mt-1 text-sm text-slate-500">Busca y filtra los precios configurados.</p>

            <form action="{{ route('precioServicio.index') }}" method="GET" class="mt-5 space-y-3">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label for="buscar" class="mb-1.5 block text-xs font-semibold text-slate-600">Buscar</label>
                        <input
                            type="search"
                            name="buscar"
                            id="buscar"
                            value="{{ $buscar }}"
                            placeholder="Servicio, tipo de vehículo o modalidad"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100"
                        >
                    </div>

                    <div>
                        <label for="tipoVehiculoId" class="mb-1.5 block text-xs font-semibold text-slate-600">Tipo de vehículo</label>
                        <select
                            name="tipoVehiculoId"
                            id="tipoVehiculoId"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100"
                        >
                            <option value="">Todos</option>
                            @foreach($tipoVehiculosOpciones as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(($filtros['tipoVehiculoId'] ?? '') == $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="modalidad" class="mb-1.5 block text-xs font-semibold text-slate-600">Modalidad</label>
                        <select
                            name="modalidad"
                            id="modalidad"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100"
                        >
                            <option value="">Todas</option>
                            <option value="fijo" @selected(($filtros['modalidad'] ?? '') === 'fijo')>Precio fijo</option>
                            <option value="desde" @selected(($filtros['modalidad'] ?? '') === 'desde')>Desde</option>
                            <option value="convenir" @selected(($filtros['modalidad'] ?? '') === 'convenir')>A convenir</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-2">
                    <a href="{{ route('precioServicio.index') }}"
                       class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Limpiar filtros
                    </a>
                    <button type="submit"
                            class="rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="p-5 sm:p-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm text-slate-500">
                    Se encontraron
                    <strong class="text-cyan-800">{{ $precioServicio->total() }}</strong>
                    precios de servicio.
                </p>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table data-datatable data-server-paginated="true" class="w-full min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-bold">Servicio</th>
                            <th class="px-5 py-4 font-bold">Tipo de vehículo</th>
                            <th class="px-5 py-4 font-bold">Duración</th>
                            <th class="px-5 py-4 font-bold">Precio</th>
                            <th class="px-5 py-4 text-center font-bold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($precioServicio as $precio)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-800">
                                        {{ $precio->servicio->nombre ?? 'N/A' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-lg bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-800">
                                        {{ $precio->tipoVehiculo->nombre ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $precio->duracionMinutos }} min
                                </td>

                                <td class="px-5 py-4">
                                    @if($precio->modalidad === 'convenir')
                                        <span class="rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700">
                                            A convenir
                                        </span>
                                    @elseif($precio->modalidad === 'desde')
                                        <span class="font-extrabold text-emerald-700">
                                            Desde ${{ number_format($precio->precio, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="font-extrabold text-cyan-800">
                                            ${{ number_format($precio->precio, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('precioServicio.edit', $precio->id) }}"
                                           class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100"
                                           title="Editar precio"
                                           aria-label="Editar precio">
                                            <span class="h-4 w-4">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>

                                        <form action="{{ route('precioServicio.destroy', $precio->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Desea eliminar este precio de servicio?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="grid h-9 w-9 place-items-center rounded-xl bg-red-50 text-red-700 transition hover:bg-red-100"
                                                    title="Eliminar precio"
                                                    aria-label="Eliminar precio">
                                                <span class="h-4 w-4">
                                                    @include('layouts.partials.icons', ['icon' => 'trash'])
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-2xl">💲</div>
                                    <p class="mt-4 font-bold text-slate-700">No hay precios para mostrar</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $buscar !== '' ? 'No encontramos resultados para tu búsqueda.' : 'Todavía no hay tarifas registradas.' }}
                                    </p>
                                    @if($buscar !== '' || !empty($filtros))
                                        <a href="{{ route('precioServicio.index') }}" class="mt-3 inline-block text-sm font-bold text-cyan-800 hover:underline">
                                            Limpiar búsqueda
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($precioServicio->hasPages())
                <div class="mt-5 border-t border-slate-100 pt-5">
                    {{ $precioServicio->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
    <x-floating-action :href="route('precioServicio.create')" label="Nuevo precio" icon="plus" />
</div>
@endsection
