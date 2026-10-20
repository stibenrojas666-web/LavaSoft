@extends('seguridad.layout')

@section('title', 'Cuentas de usuario')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Cuentas de usuario</h1>
        <a href="{{ route('seguridad.cuentas.create') }}"
            class="rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2">Nuevo usuario</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3">Usuario</th>
                    <th class="px-4 py-3">Roles</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $usuario->name }}</div>
                            <div class="text-xs text-slate-400">{{ $usuario->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse ($usuario->rolesConInactivos as $rol)
                                    <span
                                        class="rounded-full text-xs font-medium px-2 py-0.5 {{ $rol->activo ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-200 text-slate-500' }}">
                                        {{ $rol->name }}{{ $rol->activo ? '' : ' (inactivo)' }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400">Sin roles</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('seguridad.cuentas.edit', $usuario->getKey()) }}"
                                    class="text-indigo-600 hover:text-indigo-800">Editar</a>

                                @unless ($usuario->is(auth()->user()))
                                    <form action="{{ route('seguridad.cuentas.destroy', $usuario->getKey()) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar al usuario {{ $usuario->email }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
