
@extends('layouts.app')

@section('title', 'Servicios')
@section('page-title', 'Servicios')

@section('content')
    <div class="mx-auto w-full max-w-7xl space-y-5">

        {{-- Encabezado --}}
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                Servicios
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Administra los servicios que ofrece el establecimiento.
            </p>
        </div>

        {{-- Contenido --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h2 class="text-base font-semibold text-slate-800">
                    Servicios registrados
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Consulta, activa, desactiva o modifica los servicios.
                </p>
            </div>

            <div class="p-5 sm:p-6">

                {{-- Buscador y filtros --}}
                <x-list-search
                    :action="route('servicios.index')"
                    :value="$buscar"
                    placeholder="Nombre o descripción del servicio"
                    :filters="[
                        ['name' => 'estado', 'label' => 'Estado', 'options' => ['' => 'Todos', 'activo' => 'Activos', 'inactivo' => 'Inactivos'], 'value' => $filtros['estado'] ?? ''],
                    ]"
                />

                {{-- Contador --}}
                <div class="mb-4 mt-5">
                    <p class="text-sm text-slate-500">
                        <span class="font-semibold text-slate-700">{{ $servicios->total() }}</span>
                        servicios encontrados
                    </p>
                </div>

                {{-- Tabla --}}
                <div class="lavasoft-table-shell">
                    <table class="lavasoft-table min-w-[750px] text-left text-sm">

                        <thead class="bg-slate-50">
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 font-semibold text-slate-600">
                                    Nombre
                                </th>
                                <th class="px-4 py-3 font-semibold text-slate-600">
                                    Descripción
                                </th>
                                <th class="px-4 py-3 font-semibold text-slate-600">
                                    Estado
                                </th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-600">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($servicios as $servicio)
                                <tr class="transition-colors hover:bg-slate-50/70">

                                    {{-- Nombre --}}
                                    <td class="px-4 py-4">
                                        <span class="font-medium text-slate-800">
                                            {{ $servicio->nombre }}
                                        </span>
                                    </td>

                                    {{-- Descripción --}}
                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $servicio->descripcion ?: 'Sin descripción' }}
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">

                                            <form
                                                action="{{ route('servicios.estado', array_merge(['id' => $servicio->id], request()->query())) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    role="switch"
                                                    aria-checked="{{ $servicio->estado ? 'true' : 'false' }}"
                                                    aria-label="{{ $servicio->estado ? 'Desactivar' : 'Activar' }} servicio {{ $servicio->nombre }}"
                                                    title="{{ $servicio->estado ? 'Desactivar servicio' : 'Activar servicio' }}"
                                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 {{ $servicio->estado ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                                >
                                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition {{ $servicio->estado ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                </button>
                                            </form>

                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $servicio->estado ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $servicio->estado ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            <a
                                                href="{{ route('servicios.edit', $servicio->id) }}"
                                                class="inline-grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700"
                                                title="Editar servicio"
                                                aria-label="Editar servicio"
                                            >
                                                <span class="h-4 w-4">
                                                    @include('layouts.partials.icons', ['icon' => 'pencil'])
                                                </span>
                                            </a>

                                            <form
                                                action="{{ route('servicios.destroy', $servicio->id) }}"
                                                method="POST"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                                    title="Eliminar servicio"
                                                    aria-label="Eliminar servicio"
                                                    onclick="return confirm('¿Desea eliminar este servicio?')"
                                                >
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
                                    <td colspan="4" class="px-4 py-12 text-center">
                                        <p class="font-medium text-slate-700">
                                            No hay servicios para mostrar
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $buscar !== '' ? 'No encontramos servicios con esa búsqueda.' : 'No hay servicios registrados.' }}
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Paginación --}}
                @if($servicios->hasPages())
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        {{ $servicios->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <x-floating-action
        :href="route('servicios.create')"
        label="Nuevo servicio"
        icon="plus"
    />
@endsection
