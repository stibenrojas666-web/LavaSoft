
@extends('layouts.app')

@section('title', 'Crear Turno')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6">
    <x-module-header title="Registrar nuevo turno" description="Define el horario de trabajo del empleado." icon="clock" :back-url="route('turnos.index')" />
    
    <div class="modulo-panel rounded-b-2xl border-t-0 p-5 sm:p-7">
        <form action="{{ route('turnos.store') }}" method="POST" novalidate>
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="empleado_id" class="mb-1.5 block text-xs font-semibold text-slate-600">Empleado</label>
                    <select name="empleado_id" id="empleado_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('empleado_id') border-red-500 @enderror">
                        <option value="">Selecciona un empleado</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} ({{ $empleado->identificacion }})
                            </option>
                        @endforeach
                    </select>
                    @error('empleado_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="dia" class="mb-1.5 block text-xs font-semibold text-slate-600">Día</label>
                    <select name="dia" id="dia" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('dia') border-red-500 @enderror">
                        <option value="">Selecciona un día</option>
                        @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $d)
                            <option value="{{ $d }}" {{ old('dia') == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                    @error('dia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jornada" class="mb-1.5 block text-xs font-semibold text-slate-600">Jornada</label>
                    <select name="jornada" id="jornada" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('jornada') border-red-500 @enderror">
                        <option value="">Selecciona una jornada</option>
                        @foreach(['mañana','tarde','noche'] as $j)
                            <option value="{{ $j }}" {{ old('jornada') == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
                        @endforeach
                    </select>
                    @error('jornada') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="horaInicio" class="mb-1.5 block text-xs font-semibold text-slate-600">Hora de inicio</label>
                    <input type="time" name="horaInicio" id="horaInicio" value="{{ old('horaInicio') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('horaInicio') border-red-500 @enderror">
                    @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="horaFin" class="mb-1.5 block text-xs font-semibold text-slate-600">Hora de fin</label>
                    <input type="time" name="horaFin" id="horaFin" value="{{ old('horaFin') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('horaFin') border-red-500 @enderror">
                    @error('horaFin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse justify-end gap-2 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('turnos.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-cyan-600 px-5 py-2 text-sm font-semibold text-white hover:bg-cyan-700">Guardar turno</button>
            </div>
        </form>
    </div>
</div>
@endsection
