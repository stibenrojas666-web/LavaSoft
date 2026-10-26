@extends('seguridad.layout')

@section('title', 'Permisos')

@section('content')
<h1 class="text-xl font-semibold">Permisos</h1>

@include('seguridad._errores')

<x-list-search :action="route('seguridad.permisos.index')" :value="$buscar" placeholder="Nombre del permiso o módulo" />

<datalist id="lista-modulos">
    @foreach ($modulos as $modulo)
        <option value="{{ $modulo }}"></option>
    @endforeach
</datalist>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- TARJETA: CREAR PERMISO --}}
    <div class="relative overflow-hidden rounded-2xl border border-cyan-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

        <div class="absolute -right-10 -bottom-14 h-40 w-40 rounded-full bg-cyan-50"></div>

        <form action="{{ route('seguridad.permisos.store') }}" method="POST" novalidate class="relative z-10 space-y-5">
            @csrf

            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V7l8-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-800">Crear un permiso</h2>
                    <p class="text-sm text-slate-500">Define un nuevo permiso para el sistema.</p>
                </div>
            </div>

            <div>
                <label for="nuevo-nombre" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del permiso</label>
                <input type="text" name="name" id="nuevo-nombre"
                       value="{{ old('name') }}"
                       placeholder="Ej. exportar clientes"
                       class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nuevo-modulo" class="mb-2 block text-sm font-semibold text-slate-700">Módulo</label>
                <input type="text" name="modulo" id="nuevo-modulo"
                       value="{{ old('modulo') }}"
                       list="lista-modulos"
                       placeholder="Elige uno existente o escribe uno nuevo"
                       class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                @error('modulo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-700 hover:shadow-md">
                <span class="text-lg">＋</span>
                Crear permiso
            </button>
        </form>
    </div>

    {{-- TARJETA: CREAR MÓDULO --}}
    <div class="relative overflow-hidden rounded-2xl border border-sky-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

        <div class="absolute -right-10 -bottom-14 h-40 w-40 rounded-full bg-sky-50"></div>

        <form action="{{ route('seguridad.permisos.modulo') }}" method="POST" novalidate class="relative z-10 space-y-5">
            @csrf

            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 text-sky-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5L12 12l7.5-4.5M12 12v9"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-800">Crear módulo</h2>
                    <p class="text-sm text-slate-500">Genera los permisos estándar de una sola vez.</p>
                </div>
            </div>

            <p class="text-sm leading-6 text-slate-500">
                Crea de una vez: {{ collect($acciones)->map(fn ($accion) => $accion . ' …')->implode(', ') }}.
                Si alguno ya existe, solo se le asigna el módulo.
            </p>

            <div>
                <label for="modulo-estandar" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del módulo</label>
                <input type="text" name="modulo" id="modulo-estandar"
                       placeholder="Ej. clientes"
                       class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                @error('modulo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-800 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-slate-900 hover:shadow-md">
                <span class="text-lg">＋</span>
                Crear permisos del módulo
            </button>
        </form>
    </div>

</div>



@foreach ($permisos->getCollection()->groupBy(fn ($permiso) => $permiso->modulo ?? '') as $modulo => $grupo)

    <section class="mb-6 overflow-hidden rounded-2xl border border-sky-100 bg-white shadow-sm transition hover:shadow-md">

        {{-- ENCABEZADO DE LA TARJETA --}}
        <div class="flex items-center justify-between border-b border-sky-100 bg-gradient-to-r from-cyan-50 to-white px-5 py-4">

            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-100 text-cyan-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-base font-bold capitalize text-slate-800">
                        {{ $modulo === '' ? 'Sin módulo' : $modulo }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        {{ $grupo->count() }} permisos registrados
                    </p>
                </div>
            </div>

            <span class="rounded-full bg-cyan-100 px-3 py-1 text-sm font-bold text-cyan-800">
                {{ $grupo->count() }}
            </span>
        </div>

        {{-- LISTADO DE PERMISOS --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left">Permiso</th>
                        <th class="px-4 py-3 text-left">Módulo</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($grupo as $permiso)
                        <tr class="transition hover:bg-cyan-50/50">

                            <td class="px-4 py-3">
                                <input type="text"
                                       name="name"
                                       value="{{ $permiso->name }}"
                                       form="editar-{{ $permiso->id }}"
                                       class="w-full min-w-48 rounded-lg border border-slate-200 px-3 py-2 text-slate-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">

                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </td>

                            <td class="px-4 py-3">
                                <input type="text"
                                       name="modulo"
                                       value="{{ $permiso->modulo }}"
                                       form="editar-{{ $permiso->id }}"
                                       list="lista-modulos"
                                       placeholder="Sin módulo"
                                       class="w-full min-w-40 rounded-lg border border-slate-200 px-3 py-2 text-slate-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">

                                @error('modulo')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center gap-3">

                                    <form id="editar-{{ $permiso->id }}"
                                          action="{{ route('seguridad.permisos.update', $permiso) }}"
                                          method="POST"
                                          novalidate
                                          class="inline">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                                class="rounded-lg bg-cyan-50 px-3 py-2 font-semibold text-cyan-800 transition hover:bg-cyan-100">
                                            Guardar
                                        </button>
                                    </form>

                                    @if ($permiso->name !== config('seguridad.permiso_admin'))

                                        <form action="{{ route('seguridad.permisos.destroy', $permiso) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('¿Eliminar el permiso {{ $permiso->name }}?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg bg-rose-50 px-3 py-2 font-semibold text-rose-600 transition hover:bg-rose-100">
                                                Eliminar
                                            </button>
                                        </form>

                                    @else
                                        <span class="text-xs font-medium text-slate-400">Protegido</span>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </section>

@endforeach

@if($permisos->hasPages())
    <div class="mt-5">{{ $permisos->links() }}</div>
@endif
@endsection
