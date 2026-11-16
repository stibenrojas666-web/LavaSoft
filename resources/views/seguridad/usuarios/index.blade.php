@extends('layouts.app')

@section('title', 'Usuarios y roles')

@section('content')
<div class="space-y-6">
    <x-module-header variant="list" title="Usuarios y roles" description="Administra las asignaciones de roles para cada usuario." />

    @include('seguridad._errores')

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-5">
            <label for="buscarMatrizUsuarios" class="mb-2 block text-sm font-semibold text-slate-700">Buscar usuario por nombre o correo</label>
            <input id="buscarMatrizUsuarios" type="search" placeholder="Escribe una o varias palabras" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
        </div>

        <form action="{{ route('seguridad.usuarios.update') }}" method="POST" novalidate class="overflow-hidden rounded-xl border border-slate-200">
            @csrf
            @method('PUT')

            @error('roles')<p class="m-4 text-sm text-rose-700">{{ $message }}</p>@enderror
            @foreach($errors->get('roles.*') as $mensajes)
                @foreach($mensajes as $mensaje)
                    <p class="mx-4 mt-1 text-sm text-rose-700">{{ $mensaje }}</p>
                @endforeach
            @endforeach

            <div class="overflow-x-auto">
                <table class="lavasoft-table min-w-[700px] text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="sticky left-0 z-10 bg-slate-50 px-4 py-4">Usuario</th>
                            @foreach($roles as $rol)
                                <th class="px-4 py-4 text-center">
                                    {{ $rol->name }}
                                    @unless($rol->activo)
                                        <span class="mt-1 block text-xs font-normal text-slate-400">Inactivo</span>
                                    @endunless
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($usuarios as $usuario)
                            <tr data-usuario-busqueda="{{ mb_strtolower($usuario->name . ' ' . $usuario->email) }}">
                                <td class="sticky left-0 bg-white px-4 py-4">
                                    <p class="font-bold text-slate-800">{{ $usuario->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $usuario->email }}</p>
                                </td>

                                @foreach($roles as $rol)
                                    <td class="px-4 py-4 text-center">
                                        @if(in_array($rol->name, $usuario->rolesBloqueados, true))
                                            <input type="hidden" name="roles[{{ $usuario->getKey() }}][]" value="{{ $rol->name }}">
                                        @endif

                                        <input
                                            type="checkbox"
                                            value="{{ $rol->name }}"
                                            @unless(in_array($rol->name, $usuario->rolesBloqueados, true))
                                                name="roles[{{ $usuario->getKey() }}][]"
                                            @endunless
                                            @checked(in_array($rol->name, $usuario->rolesAsignados, true))
                                            @disabled(in_array($rol->name, $usuario->rolesBloqueados, true))
                                            aria-label="{{ $rol->name }} para {{ $usuario->name }}"
                                            class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600 disabled:opacity-50"
                                        >

                                        @if(in_array($rol->name, $usuario->rolesBloqueados, true))
                                            <span class="ml-1 block text-[10px] text-slate-400">Tu cuenta</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end border-t border-slate-100 bg-slate-50 px-4 py-4">
                <button type="submit" class="rounded-xl bg-cyan-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-cyan-900">
                    Guardar asignaciones
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
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
@endpush
@endsection