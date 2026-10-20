@extends('seguridad.layout')

@section('title', $rol ? 'Editar rol' : 'Nuevo rol')

@section('content')
<h1 class="text-xl font-semibold">{{ $rol ? 'Editar rol: ' . $rol->name : 'Nuevo rol' }}</h1>

@include('seguridad._errores')

<form action="{{ $rol ? route('seguridad.roles.update', $rol) : route('seguridad.roles.store') }}" method="POST"
      class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
    @csrf
    @if ($rol)
        @method('PUT')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre del rol</label>
            @if ($protegido)
                <input type="hidden" name="name" value="{{ $rol->name }}">
                <input type="text" value="{{ $rol->name }}" disabled
                       class="w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-slate-500">
                <p class="mt-1 text-xs text-slate-400">Rol protegido: no se puede renombrar ni eliminar.</p>
            @else
                <input type="text" name="name" id="name" value="{{ old('name', $rol?->name) }}" maxlength="50" required
                       class="w-full rounded-md border border-slate-300 px-3 py-2">
            @endif
        </div>

        <div>
            <span class="block text-sm font-medium text-slate-700 mb-1">Estado</span>
            @if ($noDesactivable)
                <input type="hidden" name="activo" value="1">
                <p class="text-sm text-slate-500 py-2">Activo (este rol no se puede desactivar)</p>
            @else
                <input type="hidden" name="activo" value="0">
                <label class="flex items-center gap-2 text-sm py-2">
                    <input type="checkbox" name="activo" value="1" class="rounded border-slate-300 h-4 w-4"
                           @checked(old('activo', $rol ? (int) $rol->activo : 1))>
                    Rol activo
                </label>
                <p class="text-xs text-slate-400">Un rol inactivo conserva sus permisos, pero sus usuarios no los reciben.</p>
            @endif
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-slate-700 mb-3">Permisos por módulo</h2>

        <div class="space-y-4">
            @foreach ($permisos->groupBy(fn ($permiso) => $permiso->modulo ?? '') as $modulo => $grupo)
                <fieldset class="rounded-lg border border-slate-200 p-4">
                    <legend class="px-2 text-sm font-medium capitalize">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" data-marcar-modulo="{{ $loop->index }}" class="rounded border-slate-300 h-4 w-4">
                            {{ $modulo === '' ? 'Sin módulo' : $modulo }}
                        </label>
                    </legend>

                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($grupo as $permiso)
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" name="permissions[]" value="{{ $permiso->name }}"
                                       data-modulo="{{ $loop->parent->index }}"
                                       class="rounded border-slate-300 h-4 w-4"
                                       @checked(in_array($permiso->name, $seleccionados, true))>
                                {{ $permiso->name }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach

            @if ($permisos->isEmpty())
                <p class="text-sm text-slate-500">Aún no hay permisos. Créalos en la sección Permisos.</p>
            @endif
        </div>
    </div>

    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
        <a href="{{ route('seguridad.roles.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-600">Cancelar</a>
        <button type="submit" class="rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2">Guardar</button>
    </div>
</form>

<script>
    document.querySelectorAll('[data-marcar-modulo]').forEach(function (maestro) {
        var hijos = document.querySelectorAll('input[data-modulo="' + maestro.dataset.marcarModulo + '"]');

        function sincronizar() {
            maestro.checked = Array.from(hijos).every(function (hijo) { return hijo.checked; });
        }

        maestro.addEventListener('change', function () {
            hijos.forEach(function (hijo) { hijo.checked = maestro.checked; });
        });
        hijos.forEach(function (hijo) { hijo.addEventListener('change', sincronizar); });
        sincronizar();
    });
</script>
@endsection
