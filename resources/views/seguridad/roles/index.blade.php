@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="space-y-6">
    <x-module-header variant="list" title="Roles" description="Administra los perfiles y permisos del sistema." />

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <x-list-search :action="route('seguridad.roles.index')" :value="$buscar" placeholder="Nombre del rol o permiso" />

        <p class="mb-4 mt-5 text-sm text-slate-500"><strong class="text-slate-800">{{ $roles->total() }}</strong> roles encontrados</p>

        <div class="overflow-hidden rounded-xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="lavasoft-table min-w-[850px] text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="px-4 py-4">Rol</th>
                            <th class="px-4 py-4">Estado</th>
                            <th class="px-4 py-4">Permisos</th>
                            <th class="px-4 py-4">Usuarios</th>
                            <th class="px-4 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($roles as $rol)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-4 py-4">
                                    <span class="font-bold text-slate-800">{{ $rol->name }}</span>
                                    @if($rol->protegidoParaSistema)
                                        <span class="mt-1 block text-xs font-medium text-slate-400">Protegido para el sistema</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($rol->activo)
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Activo</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-700">{{ $rol->permissions->count() }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-700">{{ $rol->users_count }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('seguridad.roles.edit', $rol) }}" class="rounded-lg bg-cyan-50 px-3 py-2 text-xs font-bold text-cyan-800 hover:bg-cyan-100">Editar</a>

                                        @unless($rol->noDesactivable)
                                            <form action="{{ route('seguridad.roles.estado', $rol) }}" method="POST"
                                                @if($rol->activo && $rol->users_count > 0)
                                                    onsubmit="return confirm('Este rol tiene {{ $rol->users_count }} usuario(s). Al desactivarlo perderán sus permisos. ¿Continuar?');"
                                                @endif
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-bold text-amber-800 hover:bg-amber-100">
                                                    {{ $rol->activo ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        @endunless

                                        @unless($rol->protegidoParaSistema)
                                            <form action="{{ route('seguridad.roles.destroy', $rol) }}" method="POST" onsubmit="return confirm('¿Eliminar el rol {{ $rol->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 hover:bg-rose-100">Eliminar</button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">
                                    {{ $buscar !== '' ? 'No encontramos roles con esa búsqueda.' : 'No hay roles registrados.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($roles->hasPages())
            <div class="mt-5">{{ $roles->links() }}</div>
        @endif
    </div>

    <x-floating-action :href="route('seguridad.roles.create')" label="Nuevo rol" />
</div>
@endsection