@php
    $nombreLavadero = $configuracionLavadero->nombre ?: config('lavadero.nombre', 'LavaSoft');
    $nit = $configuracionLavadero->nit ?: config('lavadero.nit');
    $direccion = $configuracionLavadero->direccion ?: config('lavadero.direccion');
    $telefono = $configuracionLavadero->telefono ?: config('lavadero.telefono');
    $nombreCliente = trim(($agenda->cliente->nombreCliente ?? '') . ' ' . ($agenda->cliente->apellidoCliente ?? ''));
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #cbd5e1;border-radius:8px;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <tr>
        <td style="padding:20px;text-align:center;border-bottom:1px dashed #cbd5e1;">
            @if(!empty($logoSrc))
                <img src="{{ $logoSrc }}" alt="{{ $nombreLavadero }}" style="height:56px;max-width:150px;margin-bottom:8px;"><br>
            @endif
            <span style="font-size:11px;letter-spacing:2px;color:#64748b;text-transform:uppercase;">Comprobante de servicio</span><br>
            <strong style="font-size:20px;">{{ $nombreLavadero }}</strong><br>
            @if($nit)<span style="font-size:12px;color:#64748b;">NIT: {{ $nit }}</span><br>@endif
            @if($direccion)<span style="font-size:12px;color:#64748b;">{{ $direccion }}</span><br>@endif
            @if($telefono)<span style="font-size:12px;color:#64748b;">Tel. {{ $telefono }}</span>@endif
        </td>
    </tr>
    <tr>
        <td style="padding:14px 20px;border-bottom:1px dashed #cbd5e1;">
            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                <tr>
                    <td><span style="color:#64748b;">Comprobante</span><br><strong>Cita #{{ $agenda->id }}</strong></td>
                    <td align="right"><span style="color:#64748b;">Fecha y hora</span><br><strong>{{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} · {{ substr($agenda->hora, 0, 5) }}</strong></td>
                </tr>
                <tr><td colspan="2" style="padding-top:10px;"><span style="color:#64748b;">Cliente</span><br><strong>{{ $nombreCliente ?: 'Sin cliente' }}</strong></td></tr>
                <tr><td colspan="2" style="padding-top:10px;"><span style="color:#64748b;">Atendió</span><br><strong>{{ $agenda->empleado ? trim($agenda->empleado->nombre . ' ' . $agenda->empleado->apellido) : 'Sin asignar' }}</strong></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:14px 20px;">
            @forelse($serviciosPorVehiculo as $detalleVehiculo)
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;margin-bottom:12px;">
                    <tr>
                        <td style="padding-bottom:6px;border-bottom:1px solid #e2e8f0;"><strong>{{ $detalleVehiculo['vehiculo']?->placa ?? 'Vehículo' }}</strong> <span style="color:#64748b;">· {{ $detalleVehiculo['vehiculo']?->tipoVehiculo?->nombre ?? 'Tipo sin especificar' }}</span></td>
                        <td align="right" style="padding-bottom:6px;border-bottom:1px solid #e2e8f0;color:#64748b;">Subtotal ${{ number_format($detalleVehiculo['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                    @foreach($detalleVehiculo['lineas'] as $linea)
                        <tr>
                            <td style="padding:6px 0;">{{ $linea->servicio->nombre ?? 'Servicio' }}</td>
                            <td align="right" style="padding:6px 0;">${{ number_format((float) $linea->Precio, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </table>
            @empty
                <p style="font-size:13px;color:#64748b;text-align:center;">Esta cita no tiene servicios registrados.</p>
            @endforelse
        </td>
    </tr>
    <tr>
        <td style="padding:0 20px 14px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-top:2px solid #1e293b;border-bottom:2px solid #1e293b;">
                <tr>
                    <td style="padding:12px 0;font-size:14px;font-weight:bold;text-transform:uppercase;">Total</td>
                    <td align="right" style="padding:12px 0;font-size:22px;font-weight:bold;">${{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </tr>
    @if($agenda->pago)
        <tr>
            <td style="padding:0 20px 16px;font-size:12px;color:#475569;text-align:center;">
                Pagado con {{ $agenda->pago->MetodoPago }} el {{ $agenda->pago->FechaPago?->format('d/m/Y H:i') }}<br>
                Gracias por preferirnos.
            </td>
        </tr>
    @endif
</table>