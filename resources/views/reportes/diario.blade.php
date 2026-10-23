@extends('layouts.app')

@section('title', 'Reporte diario')
@section('page-title', 'Reporte diario')

@push('styles')
    <style>
        @media print {
            body * { visibility: hidden !important; }
            #reporte-print, #reporte-print * { visibility: visible !important; }
            #reporte-print { position: absolute; inset: 0 auto auto 0; width: 100%; max-width: none; }
            .no-print { display: none !important; }
            body, #reporte-print { background: #fff !important; color: #111827 !important; }
        }
    </style>
@endpush

@section('content')
    <div id="reporte-print" class="space-y-6">
        <div class="no-print flex flex-wrap items-end justify-between gap-4">
            <form method="GET" action="{{ route('reportes.diario') }}" class="flex flex-wrap items-end gap-3">
                <x-input name="fecha" label="Día del reporte" type="date" :value="$fecha" />
                <x-button variant="cyan" type="submit">Consultar</x-button>
            </form>
            <x-button variant="secondary" type="button" onclick="window.print()">Imprimir / Guardar como PDF</x-button>
        </div>

        <x-card title="Citas atendidas" subtitle="{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-stat-card label="Citas terminadas" :value="number_format($cantidadCitas)" icon="calendar" />
                <x-stat-card label="Recaudado en pagos del día" :value="'$' . number_format($totalRecaudado, 0, ',', '.')" icon="tag" />
            </div>
        </x-card>

        <x-card title="Detalle de citas atendidas">
            <x-table :headers="['Hora', 'Cliente', 'Servicios', 'Empleado', 'Total']">
                @forelse($citas as $cita)
                    <tr>
                        <td>{{ substr($cita->hora, 0, 5) }}</td>
                        <td>{{ trim(($cita->cliente?->nombreCliente ?? '') . ' ' . ($cita->cliente?->apellidoCliente ?? '')) ?: 'Sin cliente' }}</td>
                        <td>{{ $cita->agendaServicios->pluck('servicio.nombre')->filter()->implode(', ') ?: 'Sin servicios' }}</td>
                        <td>{{ $cita->empleado ? trim($cita->empleado->nombre . ' ' . $cita->empleado->apellido) : 'Sin asignar' }}</td>
                        <td class="text-right whitespace-nowrap">${{ number_format((float) ($cita->agenda_servicios_sum_precio ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-500">No hay citas terminadas en esta fecha.</td></tr>
                @endforelse
            </x-table>
            <p class="mt-4 text-xs text-slate-500">El conteo usa la fecha de la cita terminada; el recaudo usa la fecha en que se registró cada pago.</p>
        </x-card>
    </div>
@endsection
