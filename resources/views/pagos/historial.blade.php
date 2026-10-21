@extends('layouts.app')

@section('title', 'Historial de pagos')
@section('page-title', 'Historial de pagos')

@section('content')
    <x-card title="Pagos registrados" subtitle="Un registro por cada cita pagada">
        <x-list-search :action="route('pagos.historial')" :value="$buscar" placeholder="Cliente, placa, cita o método de pago" :filters="[
            ['name' => 'metodo', 'label' => 'Método de pago', 'options' => ['' => 'Todos', 'Efectivo' => 'Efectivo', 'Tarjeta' => 'Tarjeta', 'Transferencia' => 'Transferencia'], 'value' => $filtros['metodo'] ?? ''],
            ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
            ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
        ]" />
        <p class="mb-3 text-sm text-slate-500">{{ $pagos->total() }} pagos encontrados</p>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr>
                        <th>Fecha de pago</th>
                        <th>Cliente</th>
                        <th>Vehículos</th>
                        <th>Cita</th>
                        <th>Método</th>
                        <th class="text-right">Monto</th>
                        <th>Factura</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                        @php($agenda = $pago->agenda)
                        <tr>
                            <td class="whitespace-nowrap">{{ $pago->FechaPago?->format('d/m/Y H:i') }}</td>
                            <td>{{ trim(($agenda->cliente->nombreCliente ?? '') . ' ' . ($agenda->cliente->apellidoCliente ?? '')) ?: 'Sin cliente' }}</td>
                            <td>
                                {{ $agenda->agendaServicios->pluck('vehiculo.placa')->filter()->unique()->implode(', ') ?: 'Sin vehículo' }}
                            </td>
                            <td class="whitespace-nowrap">#{{ $agenda->id }} · {{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} {{ substr($agenda->hora, 0, 5) }}</td>
                            <td>{{ $pago->MetodoPago }}</td>
                            <td class="text-right font-semibold whitespace-nowrap">${{ number_format((float) $pago->Monto, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('agenda.factura', $agenda->id) }}" class="inline-grid h-9 w-9 place-items-center rounded-lg bg-cyan-50 text-cyan-800 hover:bg-cyan-100" title="Ver factura" aria-label="Ver factura">
                                    <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'receipt'])</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-slate-500">{{ $buscar !== '' || collect($filtros)->contains(fn ($valor) => $valor !== '') ? 'No encontramos pagos con esos criterios.' : 'Todavía no hay pagos registrados.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pagos->hasPages())<div class="mt-5">{{ $pagos->links() }}</div>@endif
    </x-card>
@endsection
