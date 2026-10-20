@extends('seguridad.layout')

@section('title', 'Permisos')

@section('content')
<h1 class="text-xl font-semibold">Permisos</h1>

@include('seguridad._errores')

<datalist id="lista-modulos">
    @foreach ($modulos as $modulo)
        <option value="{{ $modulo }}"></option>
    @endforeach
</datalist>

<div class="grid gap-4 lg:grid-cols-2">
    <form action="{{ route('seguridad.permisos.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        @csrf
        <h2 class="font-semibold">Crear un permiso</h2>

        <div>
            <label for="nuevo-nombre" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
            <input type="text" name="name" id="nuevo-nombre" value="{{ old('name') }}" maxlength="100" required
                   placeholder="Ej. exportar clientes" class="w-full rounded-md border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label for="nuevo-modulo" class="block text-sm font-medium text-slate-700 mb-1">Módulo</label>
            <input type="text" name="modulo" id="nuevo-modulo" value="{{ old('modulo') }}" maxlength="50" list="lista-modulos"
                   placeholder="Elige uno existente o escribe uno nuevo" class="w-full rounded-md border border-slate-300 px-3 py-2">
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2">Crear permiso</button>
    </form>

    <form action="{{ route('seguridad.permisos.modulo') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        @csrf
        <h2 class="font-semibold">Crear un módulo con permisos estándar</h2>
        <p class="text-sm text-slate-500">
            Crea de una vez: {{ collect($acciones)->map(fn ($accion) => $accion . ' …')->implode(', ') }}.
            Si alguno ya existe, solo se le asigna el módulo.
        </p>

        <div>
            <label for="modulo-estandar" class="block text-sm font-medium text-slate-700 mb-1">Nombre del módulo</label>
            <input type="text" name="modulo" id="modulo-estandar" maxlength="50" required
                   placeholder="Ej. clientes" class="w-full rounded-md border border-slate-300 px-3 py-2">
        </div>

        <button type="submit" class="rounded-md bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2">Crear permisos del módulo</button>
    </form>
</div>

@foreach ($permisos->groupBy(fn ($permiso) => $permiso->modulo ?? '') as $modulo => $grupo)
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="px-4 py-3 border-b border-slate-100 text-sm font-semibold capitalize">
            {{ $modulo === '' ? 'Sin módulo' : $modulo }}
            <span class="font-normal text-slate-400">({{ $grupo->count() }})</span>
        </div>

        <table class="min-w-full text-sm">
            <tbody class="divide-y divide-slate-100">
                @foreach ($grupo as $permiso)
                    <tr>
                        <td class="px-4 py-2">
                            <input type="text" name="name" value="{{ $permiso->name }}" form="editar-{{ $permiso->id }}" required maxlength="100"
                                   class="w-full rounded-md border border-slate-300 px-2 py-1">
                        </td>
                        <td class="px-4 py-2">
                            <input type="text" name="modulo" value="{{ $permiso->modulo }}" form="editar-{{ $permiso->id }}" maxlength="50" list="lista-modulos"
                                   placeholder="Sin módulo" class="w-full rounded-md border border-slate-300 px-2 py-1">
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <form id="editar-{{ $permiso->id }}" action="{{ route('seguridad.permisos.update', $permiso) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-indigo-600 hover:text-indigo-800">Guardar</button>
                            </form>

                            @if ($permiso->name !== config('seguridad.permiso_admin'))
                                <form action="{{ route('seguridad.permisos.destroy', $permiso) }}" method="POST" class="inline ml-3"
                                      onsubmit="return confirm('¿Eliminar el permiso {{ $permiso->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            @else
                                <span class="ml-3 text-xs text-slate-400">protegido</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
@endsection
