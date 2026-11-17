@extends('layouts.app')

@section('title', 'Configuración del lavadero')
@section('page-title', 'Configuración del lavadero')

@section('content')
<div class="mx-auto max-w-5xl space-y-6 px-4 py-6">

    <x-module-header
        title="Configuración del lavadero"
        description="Administra los datos generales y la identidad visual de tu negocio."
        icon="cog"
    />

    @if(session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <span class="font-bold">✓</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-800">Hay algunos datos que debes revisar:</p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card
        title="Datos del negocio"
        subtitle="Esta información aparecerá en el menú y en las facturas."
    >
        <form
            action="{{ route('configuracion.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <x-input
                        name="nombre"
                        label="Nombre del lavadero"
                        :value="old('nombre', $configuracion->nombre)"
                    />
                </div>

                <x-input
                    name="nit"
                    label="NIT"
                    :value="old('nit', $configuracion->nit)"
                />

                <x-input
                    name="telefono"
                    label="Teléfono"
                    type="tel"
                    :value="old('telefono', $configuracion->telefono)"
                />

                <div class="md:col-span-2">
                    <x-input
                        name="direccion"
                        label="Dirección"
                        :value="old('direccion', $configuracion->direccion)"
                    />
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <h3 class="font-bold text-slate-900">Horario de atención</h3>
                    <p class="mt-1 text-sm text-slate-500">Las citas solo podrán reservarse dentro de estos horarios, considerando la duración del servicio.</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <x-input name="apertura_regular" label="Apertura de lunes a sábado" type="time" :value="substr((string) old('apertura_regular', $configuracion->apertura_regular ?? '07:00'), 0, 5)" />
                        <x-input name="cierre_regular" label="Cierre de lunes a sábado" type="time" :value="substr((string) old('cierre_regular', $configuracion->cierre_regular ?? '18:00'), 0, 5)" />
                        <x-input name="apertura_domingo_festivo" label="Apertura de domingos y festivos" type="time" :value="substr((string) old('apertura_domingo_festivo', $configuracion->apertura_domingo_festivo ?? '08:00'), 0, 5)" />
                        <x-input name="cierre_domingo_festivo" label="Cierre de domingos y festivos" type="time" :value="substr((string) old('cierre_domingo_festivo', $configuracion->cierre_domingo_festivo ?? '13:00'), 0, 5)" />
                    </div>
                </div>

            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-900">Identidad visual</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Configura el logo que se mostrará en el menú y en las facturas.
                    </p>
                </div>

                <label
                    for="mostrar_logo"
                    class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-cyan-200 hover:bg-cyan-50/40"
                >
                    <input
                        id="mostrar_logo"
                        name="mostrar_logo"
                        type="checkbox"
                        value="1"
                        @checked(old('mostrar_logo', $configuracion->mostrar_logo))
                        class="mt-1 h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Mostrar logo
                        </span>
                        <span class="mt-1 block text-sm text-slate-500">
                            Si desactivas esta opción y guardas, el logo dejará de aparecer en el menú y en las facturas.
                        </span>
                    </span>
                </label>

                @error('mostrar_logo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-5">
                    <label
                        for="logo"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Logo del negocio
                    </label>

                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 transition hover:border-cyan-400">
                        <input
                            id="logo"
                            name="logo"
                            type="file"
                            accept="image/*,.svg,.ico"
                            class="block w-full cursor-pointer text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-cyan-800 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-cyan-900"
                        >

                        <p class="mt-3 text-xs text-slate-500">
                            Formatos permitidos: JPG, PNG, WEBP, GIF, SVG, BMP e ICO. Tamaño máximo: 10 MB.
                        </p>

                        @error('logo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center">
                    <div id="logo-preview-container" class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        @if($configuracion->mostrar_logo && $configuracion->logo_path)
                            <img
                                id="logo-preview"
                                src="{{ route('lavadero.logo', ['v' => $configuracion->updated_at?->timestamp ?? 0]) }}"
                                alt="Logo actual de {{ $configuracion->nombre }}"
                                class="h-full w-full object-contain p-2"
                            >
                        @else
                            <div id="logo-preview-empty" class="flex flex-col items-center gap-2 text-slate-400">
                                <svg
                                    class="h-8 w-8"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    aria-hidden="true"
                                >
                                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>
                                <span class="text-xs font-medium">Sin logo</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <p class="font-semibold text-slate-800">
                            {{ $configuracion->mostrar_logo && $configuracion->logo_path ? 'Logo actual' : 'No hay un logo cargado' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            @if($configuracion->mostrar_logo && $configuracion->logo_path)
                                Este es el archivo de imagen que está guardado actualmente.
                            @else
                                Selecciona una imagen para establecer el logo de tu negocio.
                            @endif
                        </p>

                        @if($configuracion->mostrar_logo && $configuracion->logo_path)
                            <p class="mt-2 text-xs font-medium text-cyan-800">
                                Puedes seleccionar otro archivo para reemplazarlo.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                <x-button variant="secondary" :href="route('dashboard.index')">
                    Cancelar
                </x-button>
                <x-button
                    variant="cyan"
                    type="submit"
                >
                    Guardar configuración
                </x-button>
            </div>
        </form>
    </x-card>

</div>

<script>
    document.getElementById('logo')?.addEventListener('change', function () {
        const archivo = this.files?.[0];
        if (!archivo) return;

        const contenedor = this.closest('form')?.querySelector('#logo-preview-container');
        if (!contenedor) return;

        let vista = contenedor.querySelector('#logo-preview');
        if (!vista) {
            contenedor.querySelector('#logo-preview-empty')?.remove();
            vista = document.createElement('img');
            vista.id = 'logo-preview';
            vista.alt = 'Vista previa del logo seleccionado';
            vista.className = 'h-full w-full object-contain p-2';
            contenedor.appendChild(vista);
        }

        vista.src = URL.createObjectURL(archivo);
    });
</script>
@endsection
