@extends('layouts.app')

@section('title', 'Servicios')
@section('page-title', 'Servicios')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-5">

    <x-module-header
        variant="list"
        title="Servicios"
        description="Administra los servicios que ofrece el establecimiento."
    />

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Servicios registrados</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Consulta, activa, desactiva o modifica los servicios.
                    </p>
                </div>

                <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-xs font-semibold text-cyan-800">
                    {{ $servicios->total() }} registrados
                </span>
            </div>
        </div>

        <div class="p-5 sm:p-6">

            @if(session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <x-list-search
                :action="route('servicios.index')"
                :value="$buscar"
                placeholder="Nombre o descripción del servicio"
                :filters="[
                    [
                        'name' => 'estado',
                        'label' => 'Estado',
                        'options' => [
                            '' => 'Todos',
                            'activo' => 'Activos',
                            'inactivo' => 'Inactivos'
                        ],
                        'value' => $filtros['estado'] ?? ''
                    ],
                ]"
            />

            <div class="mb-4 mt-5">
                <p class="text-sm text-slate-500">
                    <span class="font-bold text-slate-800">{{ $servicios->total() }}</span>
                    servicios encontrados
                </p>
            </div>

            <div class="lavasoft-table-shell">
                <table class="lavasoft-table min-w-[750px] text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 font-semibold text-slate-600">Servicio</th>
                            <th class="px-4 py-3 font-semibold text-slate-600">Descripción</th>
                            <th class="px-4 py-3 font-semibold text-slate-600">Estado</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-600">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($servicios as $servicio)
                            <tr class="transition-colors hover:bg-slate-50/70">

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-cyan-50 p-2.5 text-cyan-800">
                                            @include('layouts.partials.icons', ['icon' => 'sparkles'])
                                        </span>

                                        <div>
                                            <p class="font-semibold text-slate-800">
                                                {{ $servicio->nombre }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-slate-400">
                                                Servicio #{{ $servicio->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="max-w-md px-4 py-4 text-slate-600">
                                    <p class="line-clamp-2">
                                        {{ $servicio->descripcion ?: 'Sin descripción' }}
                                    </p>
                                </td>

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
                                                aria-label="{{ $servicio->estado ? 'Desactivar' : 'Activar' }} servicio {{ $servicio->nombre }}"
                                                title="{{ $servicio->estado ? 'Desactivar servicio' : 'Activar servicio' }}"
                                                class="rounded-lg border px-3 py-2 text-xs font-semibold transition {{ $servicio->estado ? 'border-red-100 bg-red-50 text-red-600 hover:bg-red-100' : 'border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                            >
                                                {{ $servicio->estado ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>

                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $servicio->estado ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $servicio->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('servicios.edit', $servicio->id) }}"
                                            class="inline-grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-blue-700 transition hover:border-blue-200 hover:bg-blue-50"
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
                                            onsubmit="return confirm('¿Desea eliminar este servicio?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-red-600 transition hover:border-red-200 hover:bg-red-50"
                                                title="Eliminar servicio"
                                                aria-label="Eliminar servicio"
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
                                <td colspan="4" class="px-4 py-14 text-center">
                                    <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 p-3 text-slate-400">
                                        @include('layouts.partials.icons', ['icon' => 'sparkles'])
                                    </span>

                                    <p class="mt-4 font-semibold text-slate-700">
                                        No hay servicios para mostrar
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $buscar !== '' ? 'No encontramos servicios con esa búsqueda.' : 'No hay servicios registrados.' }}
                                    </p>

                                    @if($buscar !== '')
                                        <a
                                            href="{{ route('servicios.index') }}"
                                            class="mt-3 inline-flex text-sm font-semibold text-cyan-800 hover:underline"
                                        >
                                            Limpiar búsqueda
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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
