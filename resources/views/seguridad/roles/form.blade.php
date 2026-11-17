@extends('layouts.app')

@section('title', $rol ? 'Editar rol' : 'Nuevo rol')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <x-module-header
        :title="$rol ? 'Editar rol: ' . $rol->name : 'Nuevo rol'"
        description="Organiza los permisos y el acceso de cada perfil."
        icon="shield"
    />

    @include('seguridad._errores')

    <form action="{{ $rol ? route('seguridad.roles.update', $rol) : route('seguridad.roles.store') }}" method="POST" novalidate class="space-y-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        @if($rol)
            @method('PUT')
        @endif

        <section>
            <div class="mb-5">
                <h2 class="font-bold text-slate-900">Información del rol</h2>
                <p class="mt-1 text-sm text-slate-500">Define el nombre y el estado del perfil.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del rol</label>
                    @if($protegido)
                        <input type="hidden" name="name" value="{{ $rol->name }}">
                        <input type="text" value="{{ $rol->name }}" disabled class="min-h-11 w-full rounded-xl border border-slate-200 bg-slate-100 px-4 text-sm text-slate-500">
                        <p class="mt-2 text-xs text-slate-400">Rol protegido: no se puede renombrar ni eliminar.</p>
                    @else
                        <input type="text" name="name" id="name" value="{{ old('name', $rol?->name) }}" class="min-h-11 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100">
                        @error('name')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                    @endif
                </div>

                <div>
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Estado</span>
                    @if($noDesactivable)
                        <input type="hidden" name="activo" value="1">
                        <p class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">Activo · Este rol no se puede desactivar.</p>
                    @else
                        <input type="hidden" name="activo" value="0">
                        <label class="flex min-h-11 items-center gap-3 rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="activo" value="1" class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600" @checked(old('activo', $rol ? (int) $rol->activo : 1))>
                            Rol activo
                        </label>
                        <p class="mt-2 text-xs text-slate-400">Un rol inactivo conserva sus permisos, pero sus usuarios no los reciben.</p>
                    @endif
                    @error('activo')<p class="mt-2 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="border-t border-slate-100 pt-6">
            <div class="mb-5">
                <h2 class="font-bold text-slate-900">Permisos por módulo</h2>
                <p class="mt-1 text-sm text-slate-500">Selecciona los permisos que tendrá este rol.</p>
            </div>

            <div class="space-y-4">
                @foreach($permisos->groupBy(fn ($permiso) => $permiso->modulo ?? '') as $modulo => $grupo)
                    <fieldset class="rounded-xl border border-slate-200 p-4">
                        <legend class="px-2">
                            <label class="flex cursor-pointer items-center gap-2 text-sm font-bold capitalize text-slate-800">
                                <input type="checkbox" data-marcar-modulo="{{ $loop->index }}" class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                                {{ $modulo === '' ? 'Sin módulo' : $modulo }}
                            </label>
                        </legend>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($grupo as $permiso)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permiso->name }}"
                                        data-modulo="{{ $loop->parent->index }}"
                                        class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"
                                        @checked(in_array($permiso->name, $seleccionados, true))
                                    >
                                    {{ $permiso->name }}
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endforeach

                @if($permisos->isEmpty())
                    <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Aún no hay permisos. Créalos en la sección Permisos.</p>
                @endif
            </div>

            @error('permissions')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
            @foreach($errors->get('permissions.*') as $mensajes)
                @foreach($mensajes as $mensaje)
                    <p class="mt-2 text-sm text-rose-700">{{ $mensaje }}</p>
                @endforeach
            @endforeach
        </section>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
            <a href="{{ route('seguridad.roles.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-xl bg-cyan-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-cyan-900">Guardar rol</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.querySelectorAll('[data-marcar-modulo]').forEach(function (maestro) {
        const hijos = document.querySelectorAll('input[data-modulo="' + maestro.dataset.marcarModulo + '"]');

        function sincronizar() {
            maestro.checked = Array.from(hijos).length > 0 && Array.from(hijos).every(hijo => hijo.checked);
            maestro.indeterminate = Array.from(hijos).some(hijo => hijo.checked) && !maestro.checked;
        }

        maestro.addEventListener('change', function () {
            hijos.forEach(hijo => hijo.checked = maestro.checked);
            sincronizar();
        });

        hijos.forEach(hijo => hijo.addEventListener('change', sincronizar));
        sincronizar();
    });
</script>
@endpush
@endsection
