@extends('layouts.app')

@section('title', 'Configuración del lavadero')
@section('page-title', 'Configuración del lavadero')

@section('content')
    <x-card title="Datos del negocio" subtitle="Esta información aparecerá en el menú y en las facturas.">
        <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="nombre" label="Nombre del lavadero" :value="old('nombre', $configuracion->nombre)" />
            <x-input name="nit" label="NIT" :value="old('nit', $configuracion->nit)" />
            <x-input name="direccion" label="Dirección" :value="old('direccion', $configuracion->direccion)" />
            <x-input name="telefono" label="Teléfono" type="tel" :value="old('telefono', $configuracion->telefono)" />

            <label for="mostrar_logo" class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <input id="mostrar_logo" name="mostrar_logo" type="checkbox" value="1"
                    @checked(old('mostrar_logo', $configuracion->mostrar_logo))
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                <span>
                    <span class="block text-sm font-medium text-slate-800">Mostrar logo</span>
                    <span class="mt-1 block text-sm text-slate-500">Si lo desmarcas y guardas, se quitará del menú y de las facturas.</span>
                </span>
            </label>
            @error('mostrar_logo')<p class="-mt-4 text-sm text-red-600">{{ $message }}</p>@enderror

            <div>
                <label for="logo" class="mb-1.5 block text-sm font-medium text-slate-700">Logo (JPG, PNG o WEBP; máximo 2 MB)</label>
                <input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp"
                    class="block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                @error('logo')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                @if($configuracion->logo_path)
                    <img src="{{ '/storage/' . ltrim($configuracion->logo_path, '/') }}"
                        alt="Logo actual de {{ $configuracion->nombre }}" class="h-16 w-16 rounded-lg bg-white object-contain p-2">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-lg border border-dashed border-slate-300 text-xs text-slate-400">Sin logo</div>
                @endif
                <div>
                    <p class="font-semibold text-slate-800">{{ $configuracion->logo_path ? 'Logo actual' : 'Sin logo cargado' }}</p>
                    <p class="text-sm text-slate-500">Al desmarcar “Mostrar logo” y guardar, dejará de aparecer.</p>
                </div>
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <x-button variant="secondary" :href="route('dashboard.index')">Cancelar</x-button>
                <x-button variant="cyan" type="submit">Guardar configuración</x-button>
            </div>
        </form>
    </x-card>
@endsection
