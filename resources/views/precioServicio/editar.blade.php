
@extends('layouts.app')

@section('title', 'Editar Precio de Servicio')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <x-module-header title="Editar precio de servicio" description="Actualiza la tarifa configurada." icon="currency-dollar" :back-url="route('precioServicio.index')" />

    <form action="{{ route('precioServicio.update', $precioServicio->id) }}" method="POST" novalidate
          class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        @csrf
        @method('PUT')

        <div class="px-6 py-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600">💲</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Configuración de tarifa</h2>
                    <p class="text-xs text-slate-400">Servicio, vehículo y modalidad de cobro</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="servicio_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Servicio</label>
                    <select name="servicio_id" id="servicio_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('servicio_id') border-red-500 @enderror">
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" @selected(old('servicio_id', $precioServicio->servicio_id) == $servicio->id)>{{ $servicio->nombre }}</option>
                        @endforeach
                    </select>
                    @error('servicio_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tipo_vehiculo_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Tipo de vehículo</label>
                    <select name="tipo_vehiculo_id" id="tipo_vehiculo_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('tipo_vehiculo_id') border-red-500 @enderror">
                        @foreach($tipoVehiculos as $tipoVehiculo)
                            <option value="{{ $tipoVehiculo->id }}" @selected(old('tipo_vehiculo_id', $precioServicio->tipo_vehiculo_id) == $tipoVehiculo->id)>{{ $tipoVehiculo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_vehiculo_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="modalidad" class="block text-xs font-semibold text-slate-600 mb-1.5">Cómo se calcula</label>
                    <select name="modalidad" id="modalidad" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('modalidad') border-red-500 @enderror">
                        <option value="fijo" @selected(old('modalidad', $precioServicio->modalidad) === 'fijo')>Precio fijo</option>
                        <option value="desde" @selected(old('modalidad', $precioServicio->modalidad) === 'desde')>Desde (precio inicial)</option>
                        <option value="convenir" @selected(old('modalidad', $precioServicio->modalidad) === 'convenir')>A convenir con el cliente</option>
                    </select>
                    @error('modalidad') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
    <label for="duracionMinutos" class="block text-xs font-semibold text-slate-600 mb-1.5">
        Duración del servicio
    </label>

    <select
        name="duracionMinutos"
        id="duracionMinutos"
        required
        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('duracionMinutos') border-red-500 @enderror"
    >
        @foreach($duraciones as $minutos => $etiqueta)
            <option value="{{ $minutos }}" @selected($duracionActual === (int) $minutos)>
                {{ $etiqueta }}
            </option>
        @endforeach
    </select>

    <p class="text-xs text-slate-500 mt-1">
        Selecciona cuánto tarda este servicio para el vehículo elegido.
    </p>

    @error('duracionMinutos')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

                <div id="precio-contenedor">
                    <label for="precio" id="precio-label" class="block text-xs font-semibold text-slate-600 mb-1.5">Precio</label>
                    <input type="number" step="0.01" min="0.01" name="precio" id="precio"
                           value="{{ old('precio', $precioServicio->precio) }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('precio') border-red-500 @enderror">
                    <p id="precio-ayuda" class="text-xs text-slate-500 mt-1"></p>
                    @error('precio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
            <a href="{{ route('precioServicio.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2 rounded-lg bg-cyan-600 text-white text-sm font-semibold hover:bg-cyan-700 transition">Guardar cambios</button>
        </div>
    </form>
</div>

<script>
(() => {
    const modalidad = document.getElementById('modalidad');
    const precio = document.getElementById('precio');
    const contenedor = document.getElementById('precio-contenedor');
    const etiqueta = document.getElementById('precio-label');
    const ayuda = document.getElementById('precio-ayuda');

    function actualizarPrecio() {
        const esConvenir = modalidad.value === 'convenir';
        precio.disabled = esConvenir;
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
