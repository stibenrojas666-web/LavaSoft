@extends('layouts.app')

@section('title')
    Precio de Servicio - Editar
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Precio de Servicio
        </h1>
        <a href="{{ route('precioServicio.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('precioServicio.update', $precioServicio->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- servicio --}}
        <div class="mb-4">
            <label for="servicio_id" class="block text-gray-700 font-semibold mb-1">Servicio</label>
            <select name="servicio_id" id="servicio_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('servicio_id') border-red-500 @enderror">
                @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}"
                        {{ old('servicio_id', $precioServicio->servicio_id) == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->nombre }}
                    </option>
                @endforeach
            </select>
            @error('servicio_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- tipo de vehiculo --}}
        <div class="mb-4">
            <label for="tipo_vehiculo_id" class="block text-gray-700 font-semibold mb-1">Tipo de vehículo</label>
            <select name="tipo_vehiculo_id" id="tipo_vehiculo_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('tipo_vehiculo_id') border-red-500 @enderror">
                @foreach($tipoVehiculos as $tipoVehiculo)
                    <option value="{{ $tipoVehiculo->id }}"
                        {{ old('tipo_vehiculo_id', $precioServicio->tipo_vehiculo_id) == $tipoVehiculo->id ? 'selected' : '' }}>
                        {{ $tipoVehiculo->nombre }}
                    </option>
                @endforeach
            </select>
            @error('tipo_vehiculo_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="modalidad" class="block text-gray-700 font-semibold mb-1">Cómo se calcula</label>
            <select name="modalidad" id="modalidad" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('modalidad') border-red-500 @enderror">
                <option value="fijo" {{ old('modalidad', $precioServicio->modalidad) === 'fijo' ? 'selected' : '' }}>Precio fijo</option>
                <option value="desde" {{ old('modalidad', $precioServicio->modalidad) === 'desde' ? 'selected' : '' }}>Desde (precio inicial)</option>
                <option value="convenir" {{ old('modalidad', $precioServicio->modalidad) === 'convenir' ? 'selected' : '' }}>A convenir con el cliente</option>
            </select>
            @error('modalidad') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- precio --}}
        <div class="mb-6">
            <label for="precio" id="precio-label" class="block text-gray-700 font-semibold mb-1">Precio</label>
            <input type="number" step="0.01" min="0.01" name="precio" id="precio"
                   value="{{ old('precio', $precioServicio->precio) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('precio') border-red-500 @enderror">
            <p id="precio-ayuda" class="text-sm text-gray-500 mt-1"></p>
            @error('precio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('precioServicio.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>
            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar cambios
            </button>
        </div>
    </form>
</x-card>

<script>
    (() => {
        const modalidad = document.getElementById('modalidad');
        const precio = document.getElementById('precio');
        const etiqueta = document.getElementById('precio-label');
        const ayuda = document.getElementById('precio-ayuda');

        function actualizarPrecio() {
            const esConvenir = modalidad.value === 'convenir';
            precio.disabled = esConvenir;
            precio.required = !esConvenir;
            if (esConvenir) precio.value = '';
            precio.closest('div').classList.toggle('hidden', esConvenir);
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
