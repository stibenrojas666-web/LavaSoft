@extends('seguridad.layout')

@section('title', 'Usuarios y roles')

@section('content')
<h1 class="text-xl font-semibold">Usuarios y roles</h1>

@include('seguridad._errores')

<form action="{{ route('seguridad.usuarios.update') }}" method="POST" class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    @csrf
    @method('PUT')

    <table class="min-w-full text-sm">
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
                <tr>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $usuario->name }}</div>
                        <div class="text-xs text-slate-400">{{ $usuario->email }}</div>
                    </td>
                    @foreach ($roles as $rol)
                        @php
                            $loTiene = $usuario->rolesConInactivos->contains('name', $rol->name);
                            $bloqueado = $usuario->is(auth()->user()) && $rol->name === $rolAdmin && $loTiene;
                        @endphp
                        <td class="px-4 py-3">
                            @if ($bloqueado)
                                <input type="hidden" name="roles[{{ $usuario->getKey() }}][]" value="{{ $rol->name }}">
                            @endif
                            <input type="checkbox" value="{{ $rol->name }}"
                                   @unless ($bloqueado) name="roles[{{ $usuario->getKey() }}][]" @endunless
                                   @checked($loTiene) @disabled($bloqueado)
                                   aria-label="{{ $rol->name }} para {{ $usuario->name }}"
                                   class="rounded border-slate-300 h-4 w-4 disabled:opacity-50">
                            @if ($bloqueado)
                                <span class="ml-1 text-xs text-slate-400">tu cuenta</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end border-t border-slate-100 px-4 py-3">
        <button type="submit" class="rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2">Guardar asignaciones</button>
    </div>
</form>
@endsection
