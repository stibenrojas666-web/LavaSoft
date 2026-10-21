<h1>Factura de su servicio en LavaSoft</h1>

<p>Hola {{ $agenda->cliente->nombreCliente ?? 'cliente' }},</p>
<p>Compartimos el detalle de la cita #{{ $agenda->id }}.</p>

@include('pagos.partials.detalleFactura')

@if($agenda->pago)
    <p><strong>Método de pago:</strong> {{ $agenda->pago->MetodoPago }}</p>
    <p><strong>Fecha de pago:</strong> {{ $agenda->pago->FechaPago?->format('d/m/Y H:i') }}</p>
@endif

<p>Gracias por elegir LavaSoft.</p>
