<?php

namespace App\Mail;

use App\Models\Agenda;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Attachment;

class FacturaCitaMail extends Mailable
{
    use Queueable;

    public function __construct(public Agenda $agenda, public float $total, public array $serviciosPorVehiculo)
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
            with: ['total' => $this->total, 'serviciosPorVehiculo' => $this->serviciosPorVehiculo]
        );
    }

        public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => Pdf::loadView('emails.factura-pdf', ['agenda' => $this->agenda, 'total' => $this->total, 'serviciosPorVehiculo' => $this->serviciosPorVehiculo])->output(),
                'Factura-cita-' . $this->agenda->id . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
