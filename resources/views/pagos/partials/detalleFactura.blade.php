<div class="factura-contenido mx-auto max-w-[34rem] p-5 text-slate-800 sm:p-7">
    <header class="border-b border-dashed border-slate-300 pb-4 text-center">
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Comprobante de servicio</p>
        @if($configuracionLavadero->mostrar_logo)
            @if($configuracionLavadero->logo_path)
                <img src="{{ '/storage/' . ltrim($configuracionLavadero->logo_path, '/') }}"
                    alt="Logo de {{ $configuracionLavadero->nombre }}" class="mx-auto mb-2 h-14 max-w-36 object-contain">
            @endif
        @endif
        <h1 class="mt-1 text-xl font-bold leading-tight text-slate-950">{{ $configuracionLavadero->nombre ?: config('lavadero.nombre', 'LavaSoft') }}</h1>
        @if($configuracionLavadero->nit ?: config('lavadero.nit'))<p class="mt-1 text-xs text-slate-600">NIT: {{ $configuracionLavadero->nit ?: config('lavadero.nit') }}</p>@endif
        @if($configuracionLavadero->direccion ?: config('lavadero.direccion'))<p class="text-xs text-slate-600">{{ $configuracionLavadero->direccion ?: config('lavadero.direccion') }}</p>@endif
        @if($configuracionLavadero->telefono ?: config('lavadero.telefono'))<p class="text-xs text-slate-600">Tel. {{ $configuracionLavadero->telefono ?: config('lavadero.telefono') }}</p>@endif
    </header>

    <section class="grid grid-cols-2 gap-x-3 gap-y-2 border-b border-dashed border-slate-300 py-3 text-xs">
        <div><span class="text-slate-500">Comprobante</span><p class="font-semibold">Cita #{{ $agenda->id }}</p></div>
        <div class="text-right"><span class="text-slate-500">Fecha y hora</span><p class="font-semibold">{{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} · {{ substr($agenda->hora, 0, 5) }}</p></div>
        <div class="col-span-2"><span class="text-slate-500">Cliente</span><p class="font-semibold">{{ trim(($agenda->cliente->nombreCliente ?? '') . ' ' . ($agenda->cliente->apellidoCliente ?? '')) ?: 'Sin cliente' }}</p></div>
        @if($agenda->cliente?->emailCliente)<p class="col-span-2 break-all text-slate-600">{{ $agenda->cliente->emailCliente }}</p>@endif
        <div class="col-span-2"><span class="text-slate-500">Atendió</span><p class="font-semibold">{{ $agenda->empleado ? trim($agenda->empleado->nombre . ' ' . $agenda->empleado->apellido) : 'Sin asignar' }}</p></div>
    </section>

    <div class="py-3">
        @forelse($agenda->agendaServicios->groupBy('vehiculoId') as $lineasVehiculo)
            @php
                $vehiculoFactura = $lineasVehiculo->first()->vehiculo;
                $subtotalVehiculo = $lineasVehiculo->sum(fn ($linea) => (float) $linea->Precio);
            @endphp
            <section class="mb-3 last:mb-0">
                <div class="flex items-baseline justify-between gap-2 border-b border-slate-200 pb-1.5 text-xs">
                    <h2 class="font-semibold text-slate-800">{{ $vehiculoFactura?->placa ?? 'Vehículo' }} <span class="font-normal text-slate-500">· {{ $vehiculoFactura?->tipoVehiculo?->nombre ?? 'Tipo sin especificar' }}</span></h2>
                    <span class="shrink-0 text-slate-500">Subtotal ${{ number_format($subtotalVehiculo, 0, ',', '.') }}</span>
                </div>
                <table class="w-full text-xs">
                    <tbody>
                        @foreach($lineasVehiculo as $linea)
                            <tr>
                                <td class="py-1.5 pr-2 text-slate-700">{{ $linea->servicio->nombre ?? 'Servicio' }}</td>
                                <td class="whitespace-nowrap py-1.5 text-right font-medium text-slate-800">${{ number_format((float) $linea->Precio, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @empty
            <p class="py-4 text-center text-xs text-slate-500">Esta cita no tiene servicios registrados.</p>
        @endforelse
    </div>

    <div class="flex items-center justify-between border-y-2 border-slate-800 py-3">
        <span class="text-sm font-bold uppercase tracking-wide text-slate-800">Total</span>
        <span class="text-xl font-bold tabular-nums text-slate-950">${{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <footer class="pt-3 text-center text-[10px] text-slate-500">
        <p>Estado: {{ ucfirst($agenda->estado) }}</p>
        <p class="mt-1">Gracias por preferirnos.</p>
    </footer>
</div>
