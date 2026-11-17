@extends('layouts.app')

@section('title', 'Registrar turno')
@section('page-title', 'Registrar turno')

@section('content')
<div class="mx-auto w-full max-w-4xl space-y-6">

    <x-module-header title="Registrar turno" description="Define el horario de trabajo del empleado." icon="calendar" />

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

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 place-items-center rounded-xl bg-cyan-50 text-2xl text-cyan-800">◷</div>
                <div>
                    <h2 class="font-extrabold text-slate-800">Horario del empleado</h2>
                    <p class="mt-1 text-xs text-slate-500">Selecciona el empleado, el día y las horas de trabajo.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('turnos.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-5 px-5 py-6 sm:grid-cols-2 sm:px-7">

                <div class="sm:col-span-2">
                    <label for="empleado_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Empleado</label>
                    <select
                        name="empleado_id"
                        id="empleado_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('empleado_id') border-red-500 @enderror"
                    >
                        <option value="">Selecciona un empleado</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" @selected(old('empleado_id') == $empleado->id)>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} ({{ $empleado->identificacion }})
                            </option>
                        @endforeach
                    </select>
                    @error('empleado_id')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dia" class="mb-1.5 block text-sm font-semibold text-slate-700">Día</label>
                    <select
                        name="dia"
                        id="dia"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('dia') border-red-500 @enderror"
                    >
                        <option value="">Selecciona un día</option>
                        @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $dia)
                            <option value="{{ $dia }}" @selected(old('dia') === $dia)>
                                {{ ucfirst($dia) }}
                            </option>
                        @endforeach
                    </select>
                    @error('dia')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jornada_visual" class="mb-1.5 block text-sm font-semibold text-slate-700">Jornada</label>
                    <select
                        id="jornada_visual"
                        disabled
                        class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-600"
                    >
                        <option value="">Se calcula según la hora de inicio</option>
                        <option value="mañana">Mañana</option>
                        <option value="tarde">Tarde</option>
                        <option value="noche">Noche</option>
                    </select>
                    <p class="mt-1.5 text-xs text-slate-500">La jornada se determina automáticamente según la hora de inicio.</p>
                </div>

                <div>
                    <label for="horaInicio" class="mb-1.5 block text-sm font-semibold text-slate-700">Hora de inicio</label>
                    <input
                        type="time"
                        name="horaInicio"
                        id="horaInicio"
                        value="{{ old('horaInicio') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('horaInicio') border-red-500 @enderror"
                    >
                    @error('horaInicio')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="horaFin" class="mb-1.5 block text-sm font-semibold text-slate-700">Hora de fin</label>
                    <input
                        type="time"
                        name="horaFin"
                        id="horaFin"
                        value="{{ old('horaFin') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-3 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 @error('horaFin') border-red-500 @enderror"
                    >
                    @error('horaFin')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-form-actions :cancel-url="route('turnos.index')" save-label="Guardar turno" />
        </form>
    </div>
</div>

<script>
(() => {
    const horaInicio = document.getElementById('horaInicio');
    const jornada = document.getElementById('jornada_visual');

    if (!horaInicio || !jornada) {
        return;
    }

    function actualizarJornada() {
        if (!horaInicio.value) {
            jornada.value = '';
            return;
        }

        const hora = Number(horaInicio.value.split(':')[0]);

        jornada.value = hora < 12
            ? 'mañana'
            : (hora < 18 ? 'tarde' : 'noche');
    }

    horaInicio.addEventListener('input', actualizarJornada);
    actualizarJornada();
})();
</script>
@endsection
