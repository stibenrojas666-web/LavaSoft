<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Cita {{ $nombreLavadero }}</title></head>
<body style="font-family:Arial,sans-serif;color:#17394d;background:#f2f6fa;padding:24px">
<table role="presentation" style="max-width:620px;margin:auto;background:#fff;border-radius:16px;padding:28px;width:100%"><tr><td>
<h1 style="color:#07536c">{{ $nombreLavadero }} · {{ $accion === 'pendiente' ? 'Recibimos tu solicitud de cita' : 'Cita '.ucfirst($accion) }}</h1>
<p>Hola {{ $agenda->cliente->nombreCliente ?? 'cliente' }},</p>
<p><strong>Referencia de la cita:</strong> #{{ $agenda->referencia }}</p>
@if($primeraCita)
<p>Esta es la primera cita registrada para ti en {{ $nombreLavadero }}.</p>
@endif
@if($accion === 'pendiente')
<p>Recibimos tu solicitud para agendar una cita el {{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} a las {{ substr($agenda->hora,0,5) }}. El lavadero revisará la solicitud y te avisará si hay algún cambio.</p>
@elseif($accion === 'confirmada')
<p>Tu cita para el {{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} a las {{ substr($agenda->hora,0,5) }} quedó confirmada.</p>
@else
<p>La cita para el {{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }} a las {{ substr($agenda->hora,0,5) }} fue cancelada.</p>
@endif
<p><strong>Vehículo(s):</strong><br>@foreach($agenda->agendaServicios->pluck('vehiculo')->filter()->unique('id') as $vehiculo){{ $vehiculo->placa }} — {{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }}<br>@endforeach</p>
@if($agenda->agendaServicios->isNotEmpty())
<p><strong>Servicio(s):</strong><br>@foreach($agenda->agendaServicios as $linea){{ $linea->servicio->nombre ?? 'Servicio' }}<br>@endforeach</p>
@endif
<p>{{ $nombreLavadero }}</p>
</td></tr></table></body></html>
