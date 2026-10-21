@extends('layouts.app')

@section('title')
    Gestión de Agenda
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            📅 Agenda de Citas
        </h2>

    </div>

    <x-list-search :action="route('agenda.index')" :value="$buscar" placeholder="Cliente, placa, empleado, servicio o estado" :hidden="['mes' => $mes, 'vista' => $vista, 'fecha' => $fechaCalendario]" :filters="[
        ['name' => 'estado', 'label' => 'Estado de la cita', 'options' => ['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'terminada' => 'Terminada', 'cancelada' => 'Cancelada'], 'value' => $filtros['estado'] ?? ''],
        ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
        ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
        ['name' => 'orden', 'label' => 'Ordenar citas', 'options' => ['' => 'Predeterminado', 'fecha_desc' => 'Fecha: recientes primero', 'fecha_asc' => 'Fecha: antiguas primero', 'hora_asc' => 'Hora: temprano primero', 'hora_desc' => 'Hora: tarde primero'], 'value' => $filtros['orden'] ?? ''],
    ]" />
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500">{{ $vista === 'calendario' ? $citasCalendario->count() : $agenda->total() }} citas encontradas</p>
        <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 p-1 text-sm">
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'lista', 'mes' => $mes])) }}" class="rounded-md px-3 py-1.5 {{ $vista === 'lista' ? 'bg-white text-cyan-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">Lista</a>
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'calendario', 'mes' => $mes])) }}" class="rounded-md px-3 py-1.5 {{ $vista === 'calendario' ? 'bg-white text-cyan-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">Calendario</a>
        </div>
    </div>

    @if($vista === 'lista')
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Cliente</th>
                    <th class="py-3 px-4">Vehículo</th>
                    <th class="py-3 px-4">Empleado</th>
                    <th class="py-3 px-4">Fecha</th>
                    <th class="py-3 px-4">Hora</th>
                    <th class="py-3 px-4">Total servicios</th>
                    <th class="py-3 px-4">Atención</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                @forelse ($agenda as $a)

                <tr class="hover:bg-cyan-50">

                    <td class="px-4 py-3">{{ $a->id }}</td>

                    <td class="px-4 py-3">
                        {{ $a->cliente->nombreCliente ?? 'N/A' }}
                        {{ $a->cliente->apellidoCliente ?? '' }}
                    </td>

                    <td class="px-4 py-3">
                        @forelse($a->agendaServicios->pluck('vehiculo')->filter()->unique('id') as $vehiculo)
                            <span class="mb-1 block font-medium text-slate-800">{{ ucfirst($vehiculo->tipoVehiculo->nombre ?? 'Vehículo') }} · {{ $vehiculo->placa }}</span>
                        @empty
                            <span class="text-slate-500">Sin vehículos</span>
                        @endforelse
                    </td>

                    <td class="px-4 py-3">
                       {{ $a->empleado ? $a->empleado->nombre . ' ' . $a->empleado->apellido : 'Sin asignar' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3">
                        {{ substr($a->hora, 0, 5) }}
                    </td>

                    <td class="px-4 py-3">
                        ${{ number_format($a->agenda_servicios_sum_precio ?? 0, 2) }}
                    </td>

                    <td class="px-4 py-3">{{ $a->tipoDeAtencion }}</td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($a->estado === 'confirmada') bg-green-100 text-green-800
                            @elseif($a->estado === 'cancelada') bg-red-100 text-red-800
                            @elseif($a->estado === 'terminada') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($a->estado) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('agenda.factura', $a->id) }}"
                            class="mr-2 inline-grid h-9 w-9 place-items-center rounded-lg bg-cyan-50 text-cyan-800 hover:bg-cyan-100" title="Ver factura" aria-label="Ver factura">
                            <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'receipt'])</span>
                        </a>

                        <a href="{{ route('agenda.edit', $a->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3" title="Editar" aria-label="Editar">
                            <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                        </a>

                        <form action="{{ route('agenda.destroy', $a->id) }}"
                            method="POST"
                            class="inline">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-600 hover:text-red-900" title="Eliminar" aria-label="Eliminar"
                                onclick="return confirm('¿Desea eliminar esta cita?')">
                                <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="10" class="px-4 py-6 text-center text-gray-500">
                        {{ $buscar !== '' || collect($filtros)->contains(fn ($valor) => $valor !== '') ? 'No encontramos citas con esos criterios.' : 'No hay citas registradas.' }}
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($agenda->hasPages())<div class="mt-6">{{ $agenda->links() }}</div>@endif
    @else
        @php
            $primerDia = \Illuminate\Support\Carbon::createFromFormat('!Y-m', $mes)->startOfMonth();
            $inicioCalendario = $primerDia->copy()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
            $finCalendario = $primerDia->copy()->endOfMonth()->endOfWeek(\Illuminate\Support\Carbon::SUNDAY);
            $citasPorDia = $citasCalendario->groupBy(fn ($cita) => \Illuminate\Support\Carbon::parse($cita->fecha)->format('Y-m-d'));
            $fechaSeleccionada = \Illuminate\Support\Carbon::parse($fechaCalendario);
            if (!$fechaSeleccionada->isSameMonth($primerDia)) {
                $fechaSeleccionada = $primerDia->copy();
            }
            $fechaCalendario = $fechaSeleccionada->toDateString();
            $citasDiaSeleccionado = $citasPorDia->get($fechaCalendario, collect())->sortBy('hora');
            $mesAnterior = $primerDia->copy()->subMonth()->format('Y-m');
            $mesSiguiente = $primerDia->copy()->addMonth()->format('Y-m');
            $parametrosCalendario = array_merge(request()->query(), ['vista' => 'calendario']);
        @endphp
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-3 py-3 sm:px-5">
                <a href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $mesAnterior, 'fecha' => $primerDia->copy()->subMonth()->startOfMonth()->toDateString()])) }}" class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50" aria-label="Mes anterior">‹</a>
                <h3 class="text-base font-semibold capitalize text-slate-800">{{ $primerDia->copy()->locale('es')->translatedFormat('F Y') }}</h3>
                <a href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $mesSiguiente, 'fecha' => $primerDia->copy()->addMonth()->startOfMonth()->toDateString()])) }}" class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50" aria-label="Mes siguiente">›</a>
            </div>
            <div class="grid grid-cols-7 border-b border-slate-100 bg-slate-50 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                    <div class="px-1 py-2">{{ $dia }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7">
                @for($diaCalendario = $inicioCalendario->copy(); $diaCalendario->lte($finCalendario); $diaCalendario->addDay())
                    @php
                        $fechaCelda = $diaCalendario->format('Y-m-d');
                        $citasDelDia = $citasPorDia->get($fechaCelda, collect());
                    @endphp
                    <div class="min-h-24 border-b border-r border-slate-100 p-1.5 sm:min-h-28 sm:p-2 {{ $diaCalendario->month !== $primerDia->month ? 'bg-slate-50/70' : 'bg-white' }}">
                        <a href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $diaCalendario->format('Y-m'), 'fecha' => $fechaCelda])) }}" class="inline-grid h-6 w-6 place-items-center rounded-full text-xs {{ $fechaCelda === $fechaCalendario ? 'bg-cyan-700 font-bold text-white' : ($diaCalendario->month === $primerDia->month ? 'text-slate-700 hover:bg-cyan-50' : 'text-slate-400') }}">{{ $diaCalendario->day }}</a>
                        <div class="mt-1 space-y-1">
                            @foreach($citasDelDia->take(3) as $citaCalendario)
                                <a href="{{ route('agenda.factura', $citaCalendario->id) }}" title="{{ substr($citaCalendario->hora, 0, 5) }} · {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}" class="block truncate rounded px-1.5 py-1 text-[10px] leading-tight {{ $citaCalendario->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-800' : ($citaCalendario->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaCalendario->estado === 'terminada' ? 'bg-sky-50 text-sky-800' : 'bg-amber-50 text-amber-800')) }}">
                                    <span class="font-semibold">{{ substr($citaCalendario->hora, 0, 5) }}</span> {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}
                                </a>
                            @endforeach
                            @if($citasDelDia->count() > 3)
                                <span class="block px-1 text-[10px] text-slate-500">+{{ $citasDelDia->count() - 3 }} más</span>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
            <div class="flex flex-wrap gap-3 px-3 py-3 text-xs text-slate-600 sm:px-5">
                <span><i class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></i>Confirmada</span>
                <span><i class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-amber-400"></i>Pendiente</span>
                <span><i class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-sky-400"></i>Terminada</span>
                <span><i class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-rose-400"></i>Cancelada</span>
            </div>
        </section>

        <section class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-slate-800">Citas del {{ $fechaSeleccionada->locale('es')->translatedFormat('j \\d\\e F') }}</h3>
                <p class="text-xs text-slate-500">{{ $citasDiaSeleccionado->count() }} citas · ordenadas por hora</p>
            </div>
            @if($citasDiaSeleccionado->isEmpty())
                <p class="px-4 py-6 text-center text-sm text-slate-500">No hay citas para este día con los filtros actuales.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($citasDiaSeleccionado as $citaDia)
                        <a href="{{ route('agenda.factura', $citaDia->id) }}" class="flex flex-wrap items-center gap-x-4 gap-y-1 px-4 py-3 hover:bg-cyan-50">
                            <span class="w-14 shrink-0 font-semibold tabular-nums text-cyan-800">{{ substr($citaDia->hora, 0, 5) }}</span>
                            <span class="min-w-40 flex-1 text-sm font-medium text-slate-800">{{ trim(($citaDia->cliente->nombreCliente ?? '') . ' ' . ($citaDia->cliente->apellidoCliente ?? '')) ?: 'Cliente' }}</span>
                            <span class="text-xs text-slate-500">{{ $citaDia->agendaServicios->pluck('vehiculo.placa')->filter()->unique()->implode(', ') ?: 'Sin vehículo' }}</span>
                            <span class="rounded-full px-2 py-1 text-[11px] {{ $citaDia->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-800' : ($citaDia->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaDia->estado === 'terminada' ? 'bg-sky-50 text-sky-800' : 'bg-amber-50 text-amber-800')) }}">{{ ucfirst($citaDia->estado) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

</x-card>
<x-floating-action :href="route('agenda.create')" label="Nueva cita" icon="calendar" />

@endsection
