@extends('layouts.app')

@section('title')
    Editar Cita
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Cita
        </h1>

        <a href="{{ route('agenda.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Cliente --}}
        <div class="mb-4">
            <label for="clienteId" class="block text-gray-700 font-semibold mb-1">Cliente</label>

            <select name="clienteId" id="clienteId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('clienteId') border-red-500 @enderror">

                <option value="">Seleccione un cliente</option>

                @foreach($cliente as $c)
                    <option value="{{ $c->id }}"
                        {{ old('clienteId', $agenda->clienteId) == $c->id ? 'selected' : '' }}>
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
                        {{ old('vehiculoId', $agenda->vehiculoId) == $v->id ? 'selected' : '' }}>
                        {{ $v->placa }} - {{ $v->modelo }}
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

        {{-- Fecha y hora --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

            <div>
                <label for="fecha" class="block text-gray-700 font-semibold mb-1">Fecha</label>

                <input type="date" name="fecha" id="fecha"
                    value="{{ old('fecha', $agenda->fecha) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('fecha') border-red-500 @enderror">

                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="hora" class="block text-gray-700 font-semibold mb-1">Hora</label>

                <input type="time" name="hora" id="hora"
                    value="{{ old('hora', substr($agenda->hora, 0, 5)) }}"
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

                <option value="Por orden de llegada"
                    {{ old('tipoDeAtencion', $agenda->tipoDeAtencion) == 'Por orden de llegada' ? 'selected' : '' }}>
                    Por orden de llegada
                </option>
                <option value="Por cita agendada"
                    {{ old('tipoDeAtencion', $agenda->tipoDeAtencion) == 'Por cita agendada' ? 'selected' : '' }}>
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

                <option value="pendiente"
                    {{ old('estado', $agenda->estado) == 'pendiente' ? 'selected' : '' }}>
                    Pendiente
                </option>
                <option value="confirmada"
                    {{ old('estado', $agenda->estado) == 'confirmada' ? 'selected' : '' }}>
                    Confirmada
                </option>
                <option value="cancelada"
                    {{ old('estado', $agenda->estado) == 'cancelada' ? 'selected' : '' }}>
                    Cancelada
                </option>
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
                💾 Guardar cambios
            </button>
        </div>

    </form>

</x-card>

@endsection