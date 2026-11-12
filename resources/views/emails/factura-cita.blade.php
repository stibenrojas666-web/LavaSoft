@php
    $nombreCliente = $agenda->cliente->nombreCliente ?? 'cliente';
@endphp

<div style="margin:0;padding:24px 12px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#183445;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:720px;margin:0 auto;">
        <tr>
            <td style="padding:0 0 16px;">
                <p style="margin:0 0 5px;font-size:20px;font-weight:bold;color:#102f40;">Hola {{ $nombreCliente }},</p>
                <p style="margin:0;font-size:14px;line-height:1.7;color:#526b79;">
                    Gracias por visitarnos. Te compartimos el comprobante de tu servicio; también encontrarás una copia en PDF adjunta a este correo.
                </p>
            </td>
        </tr>
        <tr>
            <td>
                @include('emails.partials.factura', ['logoSrc' => null])
            </td>
        </tr>
        <tr>
            <td style="padding:14px 4px 0;text-align:center;font-size:12px;line-height:1.6;color:#718795;">
                Si tienes alguna pregunta, responde a este correo o comunícate con nosotros.
            </td>
        </tr>
    </table>
</div>
