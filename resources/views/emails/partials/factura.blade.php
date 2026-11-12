@php
    $nombreLavadero = $configuracionLavadero->nombre ?: config('lavadero.nombre', 'LavaSoft');
    $nit = $configuracionLavadero->nit ?: config('lavadero.nit');
    $direccion = $configuracionLavadero->direccion ?: config('lavadero.direccion');
    $telefono = $configuracionLavadero->telefono ?: config('lavadero.telefono');
    $nombreCliente = trim(($agenda->cliente->nombreCliente ?? '') . ' ' . ($agenda->cliente->apellidoCliente ?? ''));
@endphp

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:680px;margin:0 auto;border:1px solid #dbe5ec;border-top:5px solid #0891b2;border-radius:12px;overflow:hidden;background:#ffffff;font-family:Arial,Helvetica,sans-serif;color:#183445;">
    <tr>
        <td style="padding:25px 24px;background:#ffffff;text-align:center;color:#183445;">
            @if(!empty($logoSrc))
                <img src="{{ $logoSrc }}" alt="{{ $nombreLavadero }}" style="display:block;height:58px;max-width:160px;margin:0 auto 14px;object-fit:contain;">
            @endif
            <p style="margin:0 0 8px;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#087c9b;">Comprobante de servicio</p>
            <h1 style="margin:0;font-size:27px;line-height:1.2;color:#102f40;">{{ $nombreLavadero }}</h1>
            @if($nit || $direccion || $telefono)
                <p style="margin:12px 0 0;font-size:12px;line-height:1.7;color:#647b89;">
                    @if($nit) NIT: {{ $nit }} @endif
                    @if($direccion){{ $nit ? ' · ' : '' }}{{ $direccion }}@endif
                    @if($telefono)<br>Tel. {{ $telefono }}@endif
                </p>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding:20px 24px 17px;background:#f8fbfc;border-top:1px solid #e5edf1;border-bottom:1px solid #e5edf1;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="vertical-align:top;">
                        <p style="margin:0 0 5px;font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#647b89;">Factura de servicio</p>
                        <p style="margin:0;font-size:20px;font-weight:bold;color:#102f40;">Cita #{{ $agenda->id }}</p>
                    </td>
                    <td align="right" style="vertical-align:top;">
                        <p style="margin:0 0 5px;font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#647b89;">Fecha y hora</p>
                        <p style="margin:0;font-size:14px;font-weight:bold;color:#102f40;">{{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }}</p>
                        <p style="margin:3px 0 0;font-size:13px;color:#526b79;">{{ substr($agenda->hora, 0, 5) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 24px 20px;background:#f8fbfc;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border:1px solid #d8e9ee;border-radius:12px;background:#ffffff;">
                <tr>
                    <td style="padding:15px 17px;">
                        <p style="margin:0 0 5px;font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#647b89;">Cliente</p>
                        <p style="margin:0;font-size:15px;font-weight:bold;color:#102f40;">{{ $nombreCliente ?: 'Sin cliente' }}</p>
                        @if($agenda->cliente?->emailCliente)
                            <p style="margin:4px 0 0;font-size:12px;color:#647b89;">{{ $agenda->cliente->emailCliente }}</p>
                        @endif
                    </td>
                    <td align="right" style="padding:15px 17px;vertical-align:middle;">
                        <p style="margin:0 0 5px;font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#647b89;">Atendido por</p>
                        <p style="margin:0;font-size:13px;font-weight:bold;color:#102f40;">{{ $agenda->empleado ? trim($agenda->empleado->nombre . ' ' . $agenda->empleado->apellido) : 'Sin asignar' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:22px 24px 10px;">
            <h2 style="margin:0 0 16px;font-size:16px;color:#102f40;">Detalle del servicio</h2>
            @forelse($serviciosPorVehiculo as $detalleVehiculo)
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin:0 0 16px;border:1px solid #dbe5ec;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td colspan="2" style="padding:12px 14px;background:#f1f7f9;border-bottom:1px solid #dbe5ec;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="font-size:14px;font-weight:bold;color:#102f40;">{{ $detalleVehiculo['vehiculo']?->placa ?? 'Vehículo' }}</td>
                                    <td align="right" style="font-size:12px;color:#526b79;">{{ $detalleVehiculo['vehiculo']?->tipoVehiculo?->nombre ?? 'Tipo sin especificar' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @foreach($detalleVehiculo['lineas'] as $linea)
                        <tr>
                            <td style="padding:11px 14px;border-bottom:1px solid #edf2f5;font-size:13px;color:#334e5c;">{{ $linea->servicio->nombre ?? 'Servicio' }}</td>
                            <td align="right" style="padding:11px 14px;border-bottom:1px solid #edf2f5;white-space:nowrap;font-size:13px;font-weight:bold;color:#183445;">${{ number_format((float) $linea->Precio, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="padding:10px 14px;font-size:12px;font-weight:bold;color:#526b79;">Subtotal del vehículo</td>
                        <td align="right" style="padding:10px 14px;font-size:13px;font-weight:bold;color:#087c83;">${{ number_format($detalleVehiculo['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                </table>
            @empty
                <p style="margin:0;padding:18px;border:1px solid #dbe5ec;border-radius:12px;text-align:center;font-size:13px;color:#647b89;">Esta cita no tiene servicios registrados.</p>
            @endforelse
        </td>
    </tr>
    <tr>
        <td style="padding:10px 24px 22px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border:1px solid #b9e3eb;border-radius:12px;background:#eaf7fa;color:#102f40;">
                <tr>
                    <td style="padding:16px 18px;font-size:13px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#315563;">Total del servicio</td>
                    <td align="right" style="padding:14px 18px;font-size:26px;font-weight:bold;white-space:nowrap;color:#087c83;">${{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </tr>
    @if($agenda->pago)
        <tr>
            <td style="padding:0 24px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border:1px solid #b8e5d0;border-radius:12px;background:#effaf4;">
                    <tr>
                        <td style="padding:13px 15px;font-size:13px;color:#166534;">
                            <strong>Pago registrado</strong><br>
                            {{ $agenda->pago->MetodoPago }} · {{ $agenda->pago->FechaPago?->format('d/m/Y H:i') }}
                        </td>
                        <td align="right" style="padding:13px 15px;font-size:14px;font-weight:bold;color:#166534;">${{ number_format((float) $agenda->pago->Monto, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif
    <tr>
        <td style="padding:18px 24px 24px;border-top:1px solid #e5edf1;text-align:center;">
            <p style="margin:0;font-size:12px;color:#647b89;">Estado de la cita: <strong style="color:#334e5c;">{{ ucfirst($agenda->estado) }}</strong></p>
            <p style="margin:8px 0 0;font-size:13px;font-weight:bold;color:#087c83;">Gracias por preferirnos.</p>
        </td>
    </tr>
</table>
