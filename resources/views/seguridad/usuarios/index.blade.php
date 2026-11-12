@extends('layouts.app')

@section('title', 'Usuarios y roles')

@section('content')
<h1 class="text-xl font-semibold">Usuarios y roles</h1>

@include('seguridad._errores')

<div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
    <label for="buscarMatrizUsuarios" class="mb-2 block text-sm font-semibold text-slate-700">Buscar usuario por nombre o correo</label>
    <input id="buscarMatrizUsuarios" type="search" placeholder="Escribe una o varias palabras" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
</div>

<form action="{{ route('seguridad.usuarios.update') }}" method="POST" novalidate class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    @csrf
    @method('PUT')
    @error('roles')<p class="m-4 text-sm text-red-600">{{ $message }}</p>@enderror
    @foreach ($errors->get('roles.*') as $mensajes)
        @foreach ($mensajes as $mensaje)
            <p class="mx-4 mt-1 text-sm text-red-600">{{ $mensaje }}</p>
        @endforeach
    @endforeach

    <div class="lavasoft-table-shell">
    <table class="lavasoft-table min-w-[640px] text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr>
                <th class="px-4 py-3">Usuario</th>
                @foreach ($roles as $rol)
                    <th class="px-4 py-3">
                        {{ $rol->name }}
                        @unless ($rol->activo)
                            <span class="block text-xs font-normal text-slate-400">inactivo</span>
                        @endunless
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($usuarios as $usuario)
                <tr data-usuario-busqueda="{{ mb_strtolower($usuario->name . ' ' . $usuario->email) }}">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $usuario->name }}</div>
                        <div class="text-xs text-slate-400">{{ $usuario->email }}</div>
                    </td>
                    @foreach ($roles as $rol)
                        <td class="px-4 py-3">
                            @if (in_array($rol->name, $usuario->rolesBloqueados, true))
                                <input type="hidden" name="roles[{{ $usuario->getKey() }}][]" value="{{ $rol->name }}">
                            @endif
                            <input type="checkbox" value="{{ $rol->name }}"
                                   @unless (in_array($rol->name, $usuario->rolesBloqueados, true)) name="roles[{{ $usuario->getKey() }}][]" @endunless
                                   @checked(in_array($rol->name, $usuario->rolesAsignados, true)) @disabled(in_array($rol->name, $usuario->rolesBloqueados, true))
                                   aria-label="{{ $rol->name }} para {{ $usuario->name }}"
                                   class="rounded border-slate-300 h-4 w-4 disabled:opacity-50">
                            @if (in_array($rol->name, $usuario->rolesBloqueados, true))
                                <span class="ml-1 text-xs text-slate-400">tu cuenta</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <div class="flex justify-end border-t border-slate-100 px-4 py-3">
        <button type="submit" class="rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2">Guardar asignaciones</button>
    </div>
</form>
<script>
    (() => {
        const campo = document.getElementById('buscarMatrizUsuarios');
        if (!campo) return;
        const filas = [...document.querySelectorAll('[data-usuario-busqueda]')];
        campo.addEventListener('input', () => {
            const palabras = campo.value.toLocaleLowerCase().trim().split(/\s+/).filter(Boolean);
            filas.forEach(fila => {
                const texto = fila.dataset.usuarioBusqueda || '';
                fila.hidden = !palabras.every(palabra => texto.includes(palabra));
            });
        });
    })();
</script>
@endsection
