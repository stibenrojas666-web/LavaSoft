@extends('layouts.app')

@section('title', 'Cuentas de usuario')

@section('content')
<div class="space-y-6">
    <x-module-header variant="list" title="Cuentas de usuario" description="Consulta y administra las cuentas registradas." />

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <x-list-search :action="route('seguridad.cuentas.index')" :value="$buscar" placeholder="Nombre, correo o rol" />

        <div class="mb-4 mt-5 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-500"><strong class="text-slate-800">{{ $usuarios->total() }}</strong> usuarios encontrados</p>
            <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-bold text-cyan-800">Cuentas registradas</span>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200">
            <div class="overflow-x-auto">
                <table data-datatable data-server-paginated="true" class="lavasoft-table min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="px-4 py-4">Usuario</th>
                            <th class="px-4 py-4">Roles asignados</th>
                            <th class="px-4 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($usuarios as $usuario)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-800">{{ $usuario->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $usuario->email }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($usuario->rolesConInactivos as $rol)
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $rol->activo ? 'bg-cyan-50 text-cyan-800' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $rol->name }}{{ $rol->activo ? '' : ' (inactivo)' }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400">Sin roles asignados</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ route('seguridad.cuentas.edit', $usuario->getKey()) }}" class="rounded-lg bg-cyan-50 px-3 py-2 text-xs font-bold text-cyan-800 transition hover:bg-cyan-100">
                                            Editar
                                        </a>

                                        @unless($usuario->is(auth()->user()))
                                            <form action="{{ route('seguridad.cuentas.destroy', $usuario->getKey()) }}" method="POST" onsubmit="return confirm('¿Eliminar al usuario {{ $usuario->email }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-100">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-12 text-center text-sm text-slate-500">
                                    {{ $buscar !== '' ? 'No encontramos usuarios con esa búsqueda.' : 'No hay usuarios registrados.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($usuarios->hasPages())
            <div class="mt-5">{{ $usuarios->links() }}</div>
        @endif
    </div>

    <x-floating-action :href="route('seguridad.cuentas.create')" label="Nuevo usuario" />
</div>
@endsection
