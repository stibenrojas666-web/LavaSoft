
@extends('layouts.app')

@section('title', 'Agenda de citas')
@section('page-title', 'Agenda de citas')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6">

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-cyan-900 to-cyan-700 p-6 text-white shadow-lg sm:p-8">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-5">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-cyan-100">
                    <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                    Organización y programación
                </span>
                <h1 class="mt-4 text-3xl font-extrabold">Agenda de citas</h1>
                <p class="mt-2 max-w-lg text-sm text-cyan-100">Consulta, organiza y administra las citas de tus clientes.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 px-5 py-4 text-center backdrop-blur">
                <p class="text-xs font-medium text-cyan-100">Citas encontradas</p>
                <p class="mt-1 text-3xl font-extrabold">{{ $vista === 'calendario' ? $citasCalendario->count() : $agenda->total() }}</p>
            </div>
        </div>
        <div class="absolute -right-12 -top-20 h-64 w-64 rounded-full border-[35px] border-white/5"></div>
        <div class="absolute -bottom-28 right-40 h-64 w-64 rounded-full bg-cyan-400/10 blur-2xl"></div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4 flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                @include('layouts.partials.icons', ['icon' => 'search'])
            </span>
            <div>
                <h2 class="font-bold text-slate-800">Buscar y filtrar citas</h2>
                <p class="text-xs text-slate-500">Encuentra citas por cliente, placa, empleado, servicio o estado.</p>
            </div>
        </div>

        <x-list-search
            :action="route('agenda.index')"
            :value="$buscar"
            placeholder="Cliente, placa, empleado, servicio o estado"
            :hidden="['mes' => $mes, 'vista' => $vista, 'fecha' => $fechaCalendario]"
            :filters="[
                ['name' => 'estado', 'label' => 'Estado de la cita', 'options' => ['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'terminada' => 'Terminada', 'cancelada' => 'Cancelada'], 'value' => $filtros['estado'] ?? ''],
                ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
                ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
                ['name' => 'orden', 'label' => 'Ordenar citas', 'options' => ['' => 'Predeterminado', 'fecha_desc' => 'Fecha: recientes primero', 'fecha_asc' => 'Fecha: antiguas primero', 'hora_asc' => 'Hora: temprano primero', 'hora_desc' => 'Hora: tarde primero'], 'value' => $filtros['orden'] ?? ''],
            ]"
        />
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500">
            <span class="font-bold text-slate-800">{{ $vista === 'calendario' ? $citasCalendario->count() : $agenda->total() }}</span>
            citas encontradas
        </p>

        <div class="inline-flex rounded-xl border border-slate-200 bg-slate-100 p-1.5 shadow-sm">
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'lista', 'mes' => $mes])) }}"
               class="rounded-lg px-5 py-2 text-sm font-bold transition {{ $vista === 'lista' ? 'bg-cyan-700 text-white shadow-md' : 'text-slate-500 hover:bg-white hover:text-cyan-800' }}">
                Lista
            </a>
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'calendario', 'mes' => $mes])) }}"
               class="rounded-lg px-5 py-2 text-sm font-bold transition {{ $vista === 'calendario' ? 'bg-cyan-700 text-white shadow-md' : 'text-slate-500 hover:bg-white hover:text-cyan-800' }}">
                Calendario
            </a>
        </div>
    </div>

    @if($vista === 'lista')
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-[1100px] w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-bold">ID</th>
                            <th class="px-5 py-4 font-bold">Cliente</th>
                            <th class="px-5 py-4 font-bold">Vehículo</th>
                            <th class="px-5 py-4 font-bold">Empleado</th>
                            <th class="px-5 py-4 font-bold">Fecha</th>
                            <th class="px-5 py-4 font-bold">Hora</th>
                            <th class="px-5 py-4 font-bold">Total servicios</th>
                            <th class="px-5 py-4 font-bold">Atención</th>
                            <th class="px-5 py-4 font-bold">Estado</th>
                            <th class="px-4 py-4 text-center font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse ($agenda as $a)
                            <tr class="transition hover:bg-cyan-50/40">
                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-400">#{{ $a->id }}</td>
                                <td class="px-4 py-4">
                                    <span class="font-bold text-slate-800">{{ $a->cliente->nombreCliente ?? 'N/A' }} {{ $a->cliente->apellidoCliente ?? '' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @forelse($a->vehiculosParaVista as $vehiculo)
                                        <span class="mb-1 block whitespace-nowrap text-xs font-semibold text-slate-700">
                                            {{ ucfirst($vehiculo->tipoVehiculo->nombre ?? 'Vehículo') }} · {{ $vehiculo->placa }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400">Sin vehículos</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-4">{{ $a->empleado ? $a->empleado->nombre . ' ' . $a->empleado->apellido : 'Sin asignar' }}</td>
                                <td class="whitespace-nowrap px-4 py-4">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold tabular-nums">{{ substr($a->hora, 0, 5) }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-extrabold text-slate-800">${{ number_format($a->agenda_servicios_sum_precio ?? 0, 2) }}</td>
                                <td class="px-4 py-4"><span class="text-xs text-slate-600">{{ $a->tipoDeAtencion }}</span></td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold
                                        @if($a->estado === 'confirmada') bg-emerald-50 text-emerald-700
                                        @elseif($a->estado === 'cancelada') bg-rose-50 text-rose-700
                                        @elseif($a->estado === 'terminada') bg-sky-50 text-sky-700
                                        @else bg-amber-50 text-amber-700
                                        @endif">
                                        {{ ucfirst($a->estado) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('agenda.factura', $a->id) }}" class="grid h-9 w-9 place-items-center rounded-xl border border-cyan-100 bg-cyan-50 text-cyan-800 transition hover:bg-cyan-100" title="Ver factura" aria-label="Ver factura">
                                            <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'receipt'])</span>
                                        </a>
                                        <a href="{{ route('agenda.edit', $a->id) }}" class="grid h-9 w-9 place-items-center rounded-xl border border-blue-100 bg-blue-50 text-blue-700 transition hover:bg-blue-100" title="Editar" aria-label="Editar">
                                            <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                                        </a>
                                        <form action="{{ route('agenda.destroy', $a->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="grid h-9 w-9 place-items-center rounded-xl border border-rose-100 bg-rose-50 text-rose-600 transition hover:bg-rose-100" title="Eliminar" aria-label="Eliminar" onclick="return confirm('¿Desea eliminar esta cita?')">
                                                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-cyan-50 text-cyan-700">
                                            <span class="h-6 w-6">@include('layouts.partials.icons', ['icon' => 'calendar'])</span>
                                        </span>
                                        <p class="font-bold text-slate-700">
                                            {{ $buscar !== '' || collect($filtros)->contains(fn ($valor) => $valor !== '') ? 'No encontramos citas con esos criterios.' : 'No hay citas registradas.' }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">Las citas registradas aparecerán aquí.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($agenda->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                {{ $agenda->links() }}
            </div>
        @endif

    @else
<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-7">
                <a href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $mesAnterior, 'fecha' => $primerDia->copy()->subMonth()->startOfMonth()->toDateString()])) }}"
                   class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-xl text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700" aria-label="Mes anterior">‹</a>
                <h3 class="text-lg font-extrabold capitalize text-slate-800">{{ $primerDia->copy()->locale('es')->translatedFormat('F Y') }}</h3>
                <a href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $mesSiguiente, 'fecha' => $primerDia->copy()->addMonth()->startOfMonth()->toDateString()])) }}"
                   class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-xl text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700" aria-label="Mes siguiente">›</a>
            </div>

            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                    <div class="px-1 py-3">{{ $dia }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7">
                @for($diaCalendario = $inicioCalendario->copy(); $diaCalendario->lte($finCalendario); $diaCalendario->addDay())
                    <div class="min-h-24 border-b border-r border-slate-100 p-1.5 sm:min-h-28 sm:p-2 {{ $diaCalendario->month !== $primerDia->month ? 'bg-slate-50/70' : 'bg-white' }}">
                        <a href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'lista', 'mes' => $diaCalendario->format('Y-m'), 'desde' => $diaCalendario->format('Y-m-d'), 'hasta' => $diaCalendario->format('Y-m-d')])) }}"
                           class="inline-grid h-8 w-8 place-items-center rounded-full text-xs font-bold transition
                           {{ $diaCalendario->format('Y-m-d') === $fechaCalendario ? 'bg-cyan-700 text-white shadow-md' : ($diaCalendario->month === $primerDia->month ? 'text-slate-700 hover:bg-cyan-50' : 'text-slate-400 hover:bg-slate-100') }}">
                            {{ $diaCalendario->day }}
                        </a>
                        <div class="mt-1 space-y-1">
                            @foreach($citasPorDia->get($diaCalendario->format('Y-m-d'), collect())->take(3) as $citaCalendario)
                                <a href="{{ route('agenda.factura', $citaCalendario->id) }}"
                                   title="{{ substr($citaCalendario->hora, 0, 5) }} · {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}"
                                   class="block truncate rounded-lg px-1.5 py-1.5 text-[10px] leading-tight font-semibold shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
                                   {{ $citaCalendario->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-800' : ($citaCalendario->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaCalendario->estado === 'terminada' ? 'bg-sky-50 text-sky-800' : 'bg-amber-50 text-amber-800')) }}">
                                    <span class="font-extrabold">{{ substr($citaCalendario->hora, 0, 5) }}</span>
                                    {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}
                                </a>
                            @endforeach
                            @if($citasPorDia->get($diaCalendario->format('Y-m-d'), collect())->count() > 3)
                                <span class="block px-1 text-[10px] font-semibold text-slate-500">+{{ $citasPorDia->get($diaCalendario->format('Y-m-d'), collect())->count() - 3 }} más</span>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>

            <div class="flex flex-wrap gap-x-5 gap-y-2 border-t border-slate-100 px-5 py-4 text-xs text-slate-600 sm:px-7">
                <span class="inline-flex items-center gap-2"><i class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></i>Confirmada</span>
                <span class="inline-flex items-center gap-2"><i class="inline-block h-2.5 w-2.5 rounded-full bg-amber-400"></i>Pendiente</span>
                <span class="inline-flex items-center gap-2"><i class="inline-block h-2.5 w-2.5 rounded-full bg-sky-400"></i>Terminada</span>
                <span class="inline-flex items-center gap-2"><i class="inline-block h-2.5 w-2.5 rounded-full bg-rose-400"></i>Cancelada</span>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-7">
                <h3 class="font-extrabold text-slate-800">Citas del {{ $fechaSeleccionada->locale('es')->translatedFormat('j \\d\\e F') }}</h3>
                <p class="mt-1 text-xs text-slate-500">{{ $citasDiaSeleccionado->count() }} citas · Ordenadas por hora</p>
            </div>

            @if($citasDiaSeleccionado->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No hay citas para este día con los filtros actuales.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($citasDiaSeleccionado as $citaDia)
                        <a href="{{ route('agenda.factura', $citaDia->id) }}" class="flex flex-wrap items-center gap-x-4 gap-y-2 px-5 py-4 transition hover:bg-cyan-50/40 sm:px-7">
                            <span class="w-14 shrink-0 font-extrabold tabular-nums text-cyan-700">{{ substr($citaDia->hora, 0, 5) }}</span>
                            <span class="min-w-40 flex-1 text-sm font-bold text-slate-800">{{ trim(($citaDia->cliente->nombreCliente ?? '') . ' ' . ($citaDia->cliente->apellidoCliente ?? '')) ?: 'Cliente' }}</span>
                            <span class="text-xs text-slate-500">{{ $citaDia->placasVehiculosParaVista ?: 'Sin vehículo' }}</span>
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold
                                {{ $citaDia->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-700' : ($citaDia->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaDia->estado === 'terminada' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-700')) }}">
                                {{ ucfirst($citaDia->estado) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
</div>

<x-floating-action :href="route('agenda.create', ['fecha' => request('desde')])" label="Nueva cita" icon="calendar" />
@endsection
