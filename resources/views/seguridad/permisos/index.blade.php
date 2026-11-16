@extends('layouts.app')

@section('title', 'Permisos')

@section('content')
<div class="space-y-6">
    <x-module-header variant="list" title="Permisos" description="Consulta y administra los permisos de los módulos." />

    @include('seguridad._errores')

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <x-list-search :action="route('seguridad.permisos.index')" :value="$buscar" placeholder="Nombre del permiso o módulo" />
    </div>

    <datalist id="lista-modulos">
        @foreach($modulos as $modulo)
            <option value="{{ $modulo }}"></option>
        @endforeach
    </datalist>

    <div class="grid gap-5 xl:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-cyan-50 text-xl text-cyan-800">＋</span>
                <div>
                    <h2 class="font-bold text-slate-900">Crear un permiso</h2>
                    <p class="text-sm text-slate-500">Define un nuevo permiso para el sistema.</p>
                </div>
            </div>

            <form action="{{ route('seguridad.permisos.store') }}" method="POST" novalidate class="space-y-4">
                @csrf
                <div>
                    <label for="nuevo-nombre" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del permiso</label>
                    <input type="text" name="name" id="nuevo-nombre" value="{{ old('name') }}" placeholder="Ej. exportar clientes" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                    @error('name')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="nuevo-modulo" class="mb-2 block text-sm font-semibold text-slate-700">Módulo</label>
                    <input type="text" name="modulo" id="nuevo-modulo" value="{{ old('modulo') }}" list="lista-modulos" placeholder="Elige uno existente o escribe uno nuevo" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                    @error('modulo')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="rounded-xl bg-cyan-800 px-5 py-3 text-sm font-bold text-white transition hover:bg-cyan-900">Crear permiso</button>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-slate-100 text-xl text-slate-700">▦</span>
                <div>
                    <h2 class="font-bold text-slate-900">Crear módulo</h2>
                    <p class="text-sm text-slate-500">Genera los permisos estándar de una sola vez.</p>
                </div>
            </div>

            <form action="{{ route('seguridad.permisos.modulo') }}" method="POST" novalidate class="space-y-4">
                @csrf
                <p class="text-sm leading-6 text-slate-500">
                    Crea de una vez: {{ collect($acciones)->map(fn ($accion) => $accion . ' …')->implode(', ') }}.
                    Si alguno ya existe, solo se le asigna el módulo.
                </p>

                <div>
                    <label for="modulo-estandar" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del módulo</label>
                    <input type="text" name="modulo" id="modulo-estandar" placeholder="Ej. clientes" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                    @error('modulo')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="rounded-xl bg-slate-800 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-900">Crear permisos del módulo</button>
            </form>
        </section>
    </div>

    @foreach($permisos->getCollection()->groupBy(fn ($permiso) => $permiso->modulo ?? '') as $modulo => $grupo)
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4">
                <div>
                    <h2 class="font-bold capitalize text-slate-900">{{ $modulo === '' ? 'Sin módulo' : $modulo }}</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ $grupo->count() }} permisos registrados</p>
                </div>
                <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-bold text-cyan-800">{{ $grupo->count() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="lavasoft-table min-w-[700px] text-sm">
                    <thead class="bg-white text-left text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Permiso</th>
                            <th class="px-4 py-3">Módulo</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($grupo as $permiso)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3">
                                    <input type="text" name="name" value="{{ $permiso->name }}" form="editar-{{ $permiso->id }}" class="min-h-10 w-full min-w-48 rounded-lg border border-slate-300 px-3 text-sm outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100">
                                    @error('name')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="modulo" value="{{ $permiso->modulo }}" form="editar-{{ $permiso->id }}" list="lista-modulos" placeholder="Sin módulo" class="min-h-10 w-full min-w-40 rounded-lg border border-slate-300 px-3 text-sm outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100">
                                    @error('modulo')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <form id="editar-{{ $permiso->id }}" action="{{ route('seguridad.permisos.update', $permiso) }}" method="POST" novalidate>
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="rounded-lg bg-cyan-50 px-3 py-2 text-xs font-bold text-cyan-800 transition hover:bg-cyan-100">Guardar</button>
                                        </form>

                                        @if($permiso->name !== config('seguridad.permiso_admin'))
                                            <form action="{{ route('seguridad.permisos.destroy', $permiso) }}" method="POST" onsubmit="return confirm('¿Eliminar el permiso {{ $permiso->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Eliminar</button>
                                            </form>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">Protegido</span>
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
        <div>{{ $permisos->links() }}</div>
    @endif
</div>
@endsection