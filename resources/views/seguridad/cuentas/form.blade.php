@extends('seguridad.layout')

@section('title', $usuario ? 'Editar usuario' : 'Nuevo usuario')

@section('content')
<h1 class="text-xl font-semibold">{{ $usuario ? 'Editar usuario: ' . $usuario->name : 'Nuevo usuario' }}</h1>

@include('seguridad._errores')

<form action="{{ $usuario ? route('seguridad.cuentas.update', $usuario->getKey()) : route('seguridad.cuentas.store') }}" method="POST"
      class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
    @csrf
    @if ($usuario)
        @method('PUT')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $usuario?->name) }}" maxlength="255" required
                   class="w-full rounded-md border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email', $usuario?->email) }}" maxlength="255" required autocomplete="off"
                   class="w-full rounded-md border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
            <input type="password" name="password" id="password" minlength="8" autocomplete="new-password" @required(! $usuario)
                   class="w-full rounded-md border border-slate-300 px-3 py-2">
            @if ($usuario)
                <p class="mt-1 text-xs text-slate-400">Déjala vacía para conservar la actual.</p>
            @else
                <p class="mt-1 text-xs text-slate-400">Mínimo 8 caracteres.</p>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" autocomplete="new-password"
                   class="w-full rounded-md border border-slate-300 px-3 py-2">
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-slate-700 mb-3">Roles</h2>
        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($roles as $rol)
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="roles[]" value="{{ $rol->name }}" class="rounded border-slate-300 h-4 w-4"
                           @checked(in_array($rol->name, $seleccionados, true))>
                    {{ $rol->name }}
                    @unless ($rol->activo)
                        <span class="text-xs text-slate-400">(inactivo)</span>
                    @endunless
                </label>
            @endforeach

            @if ($roles->isEmpty())
                <p class="text-sm text-slate-500">Aún no hay roles. Créalos en la sección Roles.</p>
            @endif
        </div>
    </div>

    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
        <a href="{{ route('seguridad.cuentas.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-600">Cancelar</a>
        <button type="submit" class="rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2">Guardar</button>
    </div>
</form>
@endsection
