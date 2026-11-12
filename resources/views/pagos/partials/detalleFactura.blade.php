<div class="factura-contenido mx-auto max-w-[48rem] p-6 text-slate-800 sm:p-10">
    <header class="border-b border-slate-200 pb-6 text-center">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-cyan-800">Comprobante de servicio</p>
        @if($configuracionLavadero->mostrar_logo)
            @if($configuracionLavadero->logo_path)
                <img src="{{ asset('storage/' . ltrim($configuracionLavadero->logo_path, '/')) }}"
                    alt="Logo de {{ $configuracionLavadero->nombre }}" class="mx-auto mb-2 h-14 max-w-36 object-contain">
            @endif
        @endif
        <h1 class="mt-2 text-2xl font-extrabold leading-tight tracking-tight text-slate-950">{{ $configuracionLavadero->nombre ?: config('lavadero.nombre', 'LavaSoft') }}</h1>
        @if($configuracionLavadero->nit ?: config('lavadero.nit'))<p class="mt-1 text-xs text-slate-600">NIT: {{ $configuracionLavadero->nit ?: config('lavadero.nit') }}</p>@endif
        @if($configuracionLavadero->direccion ?: config('lavadero.direccion'))<p class="text-xs text-slate-600">{{ $configuracionLavadero->direccion ?: config('lavadero.direccion') }}</p>@endif
        @if($configuracionLavadero->telefono ?: config('lavadero.telefono'))<p class="text-xs text-slate-600">Tel. {{ $configuracionLavadero->telefono ?: config('lavadero.telefono') }}</p>@endif
    </header>

    <section class="grid grid-cols-2 gap-x-6 gap-y-4 border-b border-slate-200 py-6 text-sm">
        <div><span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Factura</span><p class="mt-1 font-bold text-slate-900">Cita #{{ $agenda->id }}</p></div>
        <div class="text-right"><span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Fecha y hora</span><p class="mt-1 font-bold text-slate-900">{{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} · {{ substr($agenda->hora, 0, 5) }}</p></div>
        <div class="col-span-2 rounded-2xl bg-slate-50 p-4">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Cliente</span>
            <p class="mt-1 font-bold text-slate-900">{{ trim(($agenda->cliente->nombreCliente ?? '') . ' ' . ($agenda->cliente->apellidoCliente ?? '')) ?: 'Sin cliente' }}</p>
            <div class="mt-1 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-600">
                @if($agenda->cliente?->emailCliente)<span class="break-all">{{ $agenda->cliente->emailCliente }}</span>@endif
                @if($agenda->cliente?->telefonoCliente)<span>{{ $agenda->cliente->telefonoCliente }}</span>@endif
            </div>
        </div>
        <div class="col-span-2"><span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Atendido por</span><p class="mt-1 font-semibold text-slate-800">{{ $agenda->empleado ? trim($agenda->empleado->nombre . ' ' . $agenda->empleado->apellido) : 'Sin asignar' }}</p></div>
    </section>

    <div class="py-5">
        @forelse($serviciosPorVehiculo as $detalleVehiculo)
            <section class="mb-3 last:mb-0">
                <div class="flex items-baseline justify-between gap-2 border-b border-slate-200 pb-2 text-sm">
                    <h2 class="font-bold text-slate-900">{{ $detalleVehiculo['vehiculo']?->placa ?? 'Vehículo' }} <span class="font-medium text-slate-500">· {{ $detalleVehiculo['vehiculo']?->tipoVehiculo?->nombre ?? 'Tipo sin especificar' }}</span></h2>
                    <span class="shrink-0 text-xs font-semibold text-slate-500">Subtotal ${{ number_format($detalleVehiculo['subtotal'], 0, ',', '.') }}</span>
                </div>
                <table class="lavasoft-table lavasoft-table--invoice w-full text-xs">
                    <tbody>
                        @foreach($detalleVehiculo['lineas'] as $linea)
                            <tr>
                                <td class="py-2 pr-2 text-slate-700">{{ $linea->servicio->nombre ?? 'Servicio' }}</td>
                                <td class="whitespace-nowrap py-2 text-right font-semibold text-slate-800">${{ number_format((float) $linea->Precio, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @empty
            <p class="py-4 text-center text-xs text-slate-500">Esta cita no tiene servicios registrados.</p>
        @endforelse
    </div>

    <div class="flex items-center justify-between rounded-2xl bg-navy-900 px-5 py-4 text-white">
        <span class="text-sm font-bold uppercase tracking-wider">Total a pagar</span>
        <span class="text-2xl font-extrabold tabular-nums">${{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <footer class="pt-5 text-center text-xs text-slate-500">
        <p>Estado de la cita: <span class="font-semibold text-slate-700">{{ ucfirst($agenda->estado) }}</span></p>
        <p class="mt-2">Gracias por preferirnos.</p>
    </footer>
</div>
