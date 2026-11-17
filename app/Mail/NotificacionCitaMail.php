<?php

namespace App\Mail;

use App\Models\Agenda;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NotificacionCitaMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Agenda $agenda,
        public string $accion,
        public string $nombreLavadero,
        public bool $primeraCita = false
    ) {}

    public function envelope(): Envelope
    {
        $asunto = match ($this->accion) {
            'cancelada' => 'Cita cancelada - '.$this->nombreLavadero,
            'confirmada' => 'Cita confirmada - '.$this->nombreLavadero,
            default => 'Recibimos tu solicitud de cita - '.$this->nombreLavadero,
        };

        return new Envelope(subject: $asunto);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.notificacion-cita');
    }
}
