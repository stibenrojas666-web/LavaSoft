@extends('layouts.app')

@section('title')
    Crear Turno
@endsection

@section('content')

<x-card>
    <h1 class="text-3xl font-bold mb-6 text-cyan-700">
        🕒 Registrar Nuevo Turno
    </h1>

    <form action="{{ route('turnos.store') }}" method="POST">
        @csrf

        {{-- empleado --}}
        <div class="mb-4">
            <label for="empleado_id" class="block text-gray-700 font-semibold mb-1">Empleado</label>
            <select name="empleado_id" id="empleado_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('empleado_id') border-red-500 @enderror">
                <option value="">Selecciona un empleado</option>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                        {{ $empleado->nombre }} {{ $empleado->apellido }} ({{ $empleado->identificacion }}  )
                    </option>
                @endforeach
            </select>
            @error('empleado_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- dia --}}
        <div class="mb-4">
            <label for="dia" class="block text-gray-700 font-semibold mb-1">Día</label>
            <select name="dia" id="dia"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('dia') border-red-500 @enderror">
                <option value="">Selecciona un día</option>
                @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $d)
                    <option value="{{ $d }}" {{ old('dia') == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                @endforeach
            </select>
            @error('dia')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- jornada --}}
        <div class="mb-6">
            <label for="jornada" class="block text-gray-700 font-semibold mb-1">Jornada</label>
            <select name="jornada" id="jornada"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('jornada') border-red-500 @enderror">
                <option value="">Selecciona una jornada</option>
                @foreach(['mañana','tarde','noche'] as $j)
                    <option value="{{ $j }}" {{ old('jornada') == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
                @endforeach
            </select>
            @error('jornada')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('turnos.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>
            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar
            </button>
        </div>
    </form>
</x-card>

@endsection