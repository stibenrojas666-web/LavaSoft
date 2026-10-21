<?php

namespace App\Mail;

use App\Models\agenda;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class FacturaCitaMail extends Mailable
{
    use Queueable;

    public function __construct(public agenda $agenda, public float $total)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Factura de su servicio en LavaSoft');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.factura-cita',
            with: ['total' => $this->total]
        );
    }
}
