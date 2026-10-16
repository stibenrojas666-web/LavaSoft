@extends('layouts.app')

@section('title')
    Nueva Cita
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            📅 Agendar Nueva Cita
        </h1>

        <a href="{{ route('agenda.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('agenda.store') }}" method="POST">
        @csrf

        {{-- Cliente --}}
        <div class="mb-4">
            <label for="clienteId" class="block text-gray-700 font-semibold mb-1">Cliente</label>

            <select name="clienteId" id="clienteId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('clienteId') border-red-500 @enderror">

                <option value="">Seleccione un cliente</option>

                @foreach($cliente as $c)
                    <option value="{{ $c->id }}"
                        {{ old('clienteId') == $c->id ? 'selected' : '' }}>
                        {{ $c->nombreCliente }} {{ $c->apellidoCliente }}
                    </option>
                @endforeach
            </select>

            @error('clienteId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vehículo --}}
        <div class="mb-4">
            <label for="vehiculoId" class="block text-gray-700 font-semibold mb-1">Vehículo</label>

            <select name="vehiculoId" id="vehiculoId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('vehiculoId') border-red-500 @enderror">

                <option value="">Seleccione un vehículo</option>

                @foreach($vehiculo as $v)
                    <option value="{{ $v->id }}"
                        data-tipo="{{ $v->tipoVehiculoId }}"
                        {{ old('vehiculoId') == $v->id ? 'selected' : '' }}>
                        {{ $v->tipoVehiculo->nombre ?? 'Tipo sin registrar' }} - {{ $v->placa }}  - {{ $v->modelo }}
                    </option>
                @endforeach
            </select>

            @error('vehiculoId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Empleado (opcional) --}}
        <div class="mb-4">
            <label for="empleadoId" class="block text-gray-700 font-semibold mb-1">
                Empleado <span class="text-gray-400 font-normal">(opcional)</span>
            </label>

            <select name="empleadoId" id="empleadoId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('empleadoId') border-red-500 @enderror">

                <option value="">Sin asignar</option>

                @foreach($empleado as $e)
                    <option value="{{ $e->id }}"
                        {{ old('empleadoId') == $e->id ? 'selected' : '' }}>
                        {{ $e->nombre }} {{ $e->apellido }}
                    </option>
                @endforeach
            </select>

            @error('empleadoId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Servicios de la cita --}}
        <div class="mb-6">
            <div class="mb-3">
                <h2 class="text-lg font-semibold text-slate-800">Servicios para esta cita</h2>
                <p class="mt-1 text-sm text-slate-500">Puedes seleccionar uno o varios servicios.</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                @forelse($servicio as $s)
                    @php
                        $preciosPorTipo = $s->precios->keyBy('tipo_vehiculo_id')->map(fn ($precio) => [
                            'modalidad' => $precio->modalidad,
                            'precio' => $precio->precio,
                        ]);
                    @endphp
                    <div class="border-b border-slate-100 last:border-b-0" data-servicio-option>
                    <label class="block cursor-pointer">
                        <input
                            type="checkbox"
                            name="servicios[]"
                            value="{{ $s->id }}"
                            class="peer sr-only"
                            data-servicio-id="{{ $s->id }}"
                            data-precios='@json($preciosPorTipo)'
                            {{ in_array($s->id, old('servicios', [])) ? 'checked' : '' }}>

                        <span class="flex min-h-14 items-center gap-3 px-4 py-3 text-sm transition before:flex before:h-5 before:w-5 before:shrink-0 before:items-center before:justify-center before:rounded before:border before:border-slate-300 before:text-xs before:text-transparent before:transition before:content-['✓'] hover:bg-cyan-50 peer-checked:bg-cyan-50 peer-checked:text-cyan-800 peer-checked:before:border-cyan-600 peer-checked:before:bg-cyan-600 peer-checked:before:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-inset peer-focus-visible:ring-cyan-500 peer-disabled:cursor-not-allowed peer-disabled:opacity-50">
                            <span class="font-medium">{{ $s->nombre }}</span>
                            <span class="ml-auto text-right text-xs font-semibold text-slate-500" data-precio-label>Selecciona un vehículo</span>
                        </span>
                    </label>
                    <div class="hidden px-12 pb-3" data-precio-acordado="{{ $s->id }}">
                        <label for="precio-acordado-{{ $s->id }}" class="block text-xs font-medium text-slate-600">Precio acordado con el cliente</label>
                        <input type="number" min="0.01" step="0.01" inputmode="decimal"
                            id="precio-acordado-{{ $s->id }}" name="precios_acordados[{{ $s->id }}]"
                            value="{{ old('precios_acordados.' . $s->id) }}" disabled
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200">
                        @if($errors->has('precios_acordados.' . $s->id))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('precios_acordados.' . $s->id) }}</p>
                        @endif
                    </div>
                    </div>
                @empty
                    <p class="px-4 py-3 text-sm text-slate-500">No hay servicios registrados.</p>
                @endforelse
            </div>

            @error('servicios')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha y hora --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

            <div>
                <label for="fecha" class="block text-gray-700 font-semibold mb-1">Fecha</label>

                <input type="date" name="fecha" id="fecha"
                    value="{{ old('fecha') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('fecha') border-red-500 @enderror">

                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="hora" class="block text-gray-700 font-semibold mb-1">Hora</label>

                <input type="time" name="hora" id="hora"
                    value="{{ old('hora') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('hora') border-red-500 @enderror">

                @error('hora')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Tipo de atención --}}
        <div class="mb-4">
            <label for="tipoDeAtencion" class="block text-gray-700 font-semibold mb-1">Tipo de atención</label>

            <select name="tipoDeAtencion" id="tipoDeAtencion"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('tipoDeAtencion') border-red-500 @enderror">

                <option value="">Seleccione un tipo</option>
                <option value="Por orden de llegada" {{ old('tipoDeAtencion') == 'Por orden de llegada' ? 'selected' : '' }}>
                    Por orden de llegada
                </option>
                <option value="Por cita agendada" {{ old('tipoDeAtencion') == 'Por cita agendada' ? 'selected' : '' }}>
                    Por cita agendada
                </option>
            </select>

            @error('tipoDeAtencion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-6">
            <label for="estado" class="block text-gray-700 font-semibold mb-1">Estado</label>

            <select name="estado" id="estado"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('estado') border-red-500 @enderror">

                <option value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmada" {{ old('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>

            @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('agenda.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>

            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar Cita
            </button>
        </div>

    </form>

</x-card>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const vehiculo = document.getElementById('vehiculoId');
        const servicios = document.querySelectorAll('[data-servicio-id]');
        const formatoPesos = new Intl.NumberFormat('es-CO', {
            style: 'currency', currency: 'COP', maximumFractionDigits: 0
        });

        function actualizarPrecios() {
            const tipoId = vehiculo.selectedOptions[0]?.dataset.tipo;

            servicios.forEach((checkbox) => {
                const opcion = checkbox.closest('[data-servicio-option]');
                const etiqueta = opcion.querySelector('[data-precio-label]');
                const contenedorPrecio = opcion.querySelector(`[data-precio-acordado="${checkbox.dataset.servicioId}"]`);
                const inputPrecio = contenedorPrecio.querySelector('input');
                const configuraciones = JSON.parse(checkbox.dataset.precios || '{}');
                const configuracion = tipoId ? configuraciones[tipoId] : null;

                if (!tipoId) {
                    etiqueta.textContent = 'Selecciona un vehículo';
                    checkbox.disabled = false;
                    contenedorPrecio.classList.add('hidden');
                    inputPrecio.disabled = true;
                    inputPrecio.required = false;
                    return;
                }

                if (!configuracion) {
                    etiqueta.textContent = 'No disponible para este tipo';
                    checkbox.checked = false;
                    checkbox.disabled = true;
                    contenedorPrecio.classList.add('hidden');
                    inputPrecio.disabled = true;
                    inputPrecio.required = false;
                    inputPrecio.value = '';
                    return;
                }

                checkbox.disabled = false;
                if (configuracion.modalidad === 'convenir') {
                    etiqueta.textContent = 'A convenir';
                    contenedorPrecio.classList.toggle('hidden', !checkbox.checked);
                    inputPrecio.disabled = !checkbox.checked;
                    inputPrecio.required = checkbox.checked;
                } else {
                    const importe = formatoPesos.format(Number(configuracion.precio));
                    etiqueta.textContent = configuracion.modalidad === 'desde' ? `Desde ${importe}` : importe;
                    contenedorPrecio.classList.add('hidden');
                    inputPrecio.disabled = true;
                    inputPrecio.required = false;
                    inputPrecio.value = '';
                }
            });
        }

        vehiculo.addEventListener('change', actualizarPrecios);
        servicios.forEach((checkbox) => checkbox.addEventListener('change', actualizarPrecios));
        actualizarPrecios();
    });
</script>
@endpush

@endsection
