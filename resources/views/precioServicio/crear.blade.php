@extends('layouts.app')

@section('title', 'Registrar precio de servicio')
@section('page-title', 'Registrar precio')

@section('content')
<div class="mx-auto w-full max-w-5xl space-y-6">

    <x-module-header title="Registrar precio de servicio" description="Define la tarifa y duración según el tipo de vehículo." icon="tag" />

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="mb-2 text-sm font-bold text-red-800">Revisa los siguientes campos:</p>
            <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('precioServicio.store') }}" method="POST" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf

        <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 place-items-center rounded-xl bg-cyan-50 text-2xl">💲</div>
                <div>
                    <h2 class="font-extrabold text-slate-800">Configuración de tarifa</h2>
                    <p class="mt-1 text-xs text-slate-500">Completa los datos del servicio y su precio.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 px-5 py-6 sm:grid-cols-2 sm:px-7">

            <div>
                <label for="servicio_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Servicio</label>
                <select name="servicio_id" id="servicio_id"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('servicio_id') border-red-500 @enderror">
                    <option value="">Selecciona un servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" @selected(old('servicio_id') == $servicio->id)>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('servicio_id')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tipo_vehiculo_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Tipo de vehículo</label>
                <select name="tipo_vehiculo_id" id="tipo_vehiculo_id"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('tipo_vehiculo_id') border-red-500 @enderror">
                    <option value="">Selecciona un tipo de vehículo</option>
                    @foreach($tipoVehiculos as $tipoVehiculo)
                        <option value="{{ $tipoVehiculo->id }}" @selected(old('tipo_vehiculo_id') == $tipoVehiculo->id)>
                            {{ $tipoVehiculo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_vehiculo_id')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="modalidad" class="mb-1.5 block text-sm font-semibold text-slate-700">Modalidad de cobro</label>
                <select name="modalidad" id="modalidad"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('modalidad') border-red-500 @enderror">
                    <option value="fijo" @selected(old('modalidad', 'fijo') === 'fijo')>Precio fijo</option>
                    <option value="desde" @selected(old('modalidad') === 'desde')>Desde (precio inicial)</option>
                    <option value="convenir" @selected(old('modalidad') === 'convenir')>A convenir con el cliente</option>
                </select>
                @error('modalidad')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="duracionMinutos" class="mb-1.5 block text-sm font-semibold text-slate-700">Duración del servicio</label>
                <select name="duracionMinutos" id="duracionMinutos" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('duracionMinutos') border-red-500 @enderror">
                    @foreach($duraciones as $minutos => $etiqueta)
                        <option value="{{ $minutos }}" @selected(old('duracionMinutos', 30) == $minutos)>
                            {{ $etiqueta }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-slate-500">Tiempo estimado para realizar este servicio.</p>
                @error('duracionMinutos')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="precio-contenedor" class="sm:col-span-2">
                <label for="precio" id="precio-label" class="mb-1.5 block text-sm font-semibold text-slate-700">Precio</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-semibold text-slate-400">$</span>
                    <input type="number" step="0.01" min="0.01" name="precio" id="precio"
                           value="{{ old('precio') }}"
                           class="w-full rounded-xl border border-slate-200 py-3 pl-9 pr-4 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('precio') border-red-500 @enderror">
                </div>
                <p id="precio-ayuda" class="mt-1.5 text-xs text-slate-500"></p>
                @error('precio')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <x-form-actions :cancel-url="route('precioServicio.index')" save-label="Guardar precio" />
    </form>
</div>

<script>
(() => {
    const modalidad = document.getElementById('modalidad');
    const precio = document.getElementById('precio');
    const contenedor = document.getElementById('precio-contenedor');
    const etiqueta = document.getElementById('precio-label');
    const ayuda = document.getElementById('precio-ayuda');

    if (!modalidad || !precio || !contenedor || !etiqueta || !ayuda) {
        return;
    }

    function actualizarPrecio() {
        const esConvenir = modalidad.value === 'convenir';
        precio.disabled = esConvenir;
        precio.required = !esConvenir;
        contenedor.classList.toggle('hidden', esConvenir);

        etiqueta.textContent = modalidad.value === 'desde' ? 'Precio inicial' : 'Precio';

        ayuda.textContent = modalidad.value === 'desde'
            ? 'Este valor se mostrará como precio desde.'
            : (esConvenir ? 'El valor acordado se escribe al agendar la cita.' : '');
    }

    modalidad.addEventListener('change', actualizarPrecio);
    actualizarPrecio();
})();
</script>
@endsection
