@extends('layouts.app')

@section('title', $usuario ? 'Editar usuario' : 'Nuevo usuario')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <x-module-header
        :title="$usuario ? 'Editar usuario: ' . $usuario->name : 'Nuevo usuario'"
        description="Administra la información de acceso y los roles de cada cuenta."
        icon="user"
    />

    @include('seguridad._errores')

    <form
        action="{{ $usuario ? route('seguridad.cuentas.update', $usuario->getKey()) : route('seguridad.cuentas.store') }}"
        method="POST"
        novalidate
        class="space-y-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"
    >
        @csrf
        @if($usuario)
            @method('PUT')
        @endif

        <section>
            <div class="mb-5">
                <h2 class="font-bold text-slate-900">Información de la cuenta</h2>
                <p class="mt-1 text-sm text-slate-500">Datos de identificación e inicio de sesión.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $usuario?->name) }}" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                    @error('name')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $usuario?->email) }}" autocomplete="off" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                    @error('email')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" minlength="8" autocomplete="new-password" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 pr-12 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                        <button type="button" data-password-toggle="password" aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false" class="absolute inset-y-0 right-2 grid w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-cyan-50 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <span data-password-eye class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                            <span data-password-eye-off class="block h-5 w-5" style="display: none">@include('layouts.partials.icons', ['icon' => 'eye-off'])</span>
                        </button>
                    </div>
                    @error('password')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                    <p class="mt-2 text-xs text-slate-400">{{ $usuario ? 'Déjala vacía para conservar la actual.' : 'Mínimo 8 caracteres.' }}</p>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirmar contraseña</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" autocomplete="new-password" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 pr-12 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                        <button type="button" data-password-toggle="password_confirmation" aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false" class="absolute inset-y-0 right-2 grid w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-cyan-50 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <span data-password-eye class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                            <span data-password-eye-off class="block h-5 w-5" style="display: none">@include('layouts.partials.icons', ['icon' => 'eye-off'])</span>
                        </button>
                    </div>
                    @error('password_confirmation')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="border-t border-slate-100 pt-6">
            <div class="mb-4">
                <h2 class="font-bold text-slate-900">Roles de usuario</h2>
                <p class="mt-1 text-sm text-slate-500">Selecciona los roles que tendrá asignados esta cuenta.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($roles as $rol)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 text-sm transition hover:border-cyan-300 hover:bg-cyan-50/40">
                        @if(!$rol->activo && in_array($rol->name, $seleccionados, true))
                            <input type="hidden" name="roles[]" value="{{ $rol->name }}">
                        @endif

                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $rol->name }}"
                            class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"
                            @checked(in_array($rol->name, $seleccionados, true))
                            @disabled(!$rol->activo)
                        >

                        <span class="min-w-0">
                            <span class="block font-semibold text-slate-800">{{ $rol->name }}</span>
                            @unless($rol->activo)
                                <span class="mt-1 block text-xs text-slate-400">Inactivo</span>
                            @endunless
                        </span>
                    </label>
                @endforeach

                @if($roles->isEmpty())
                    <p class="text-sm text-slate-500">Aún no hay roles. Créalos en la sección Roles.</p>
                @endif
            </div>

            @error('roles')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
            @foreach($errors->get('roles.*') as $mensajes)
                @foreach($mensajes as $mensaje)
                    <p class="mt-2 text-sm text-rose-700">{{ $mensaje }}</p>
                @endforeach
            @endforeach
        </section>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
            <a href="{{ route('seguridad.cuentas.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-xl bg-cyan-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-cyan-900">Guardar cuenta</button>
        </div>
    </form>
</div>
@endsection
