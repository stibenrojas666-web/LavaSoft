<?php

namespace App\Services;

use App\Mail\NotificacionCitaMail;
use App\Models\Agenda;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificacionCitaService
{
    public function __construct(private ConfiguracionLavaderoService $configuracionLavadero) {}

    public function enviarSolicitud(Agenda $agenda): bool
    {
        $agenda->loadMissing(['cliente', 'agendaServicios.vehiculo.tipoVehiculo', 'agendaServicios.servicio']);
        $correo = trim((string) $agenda->cliente?->emailCliente);
        if ($correo === '' || $agenda->estado === 'cancelada') {
            return false;
        }

        if (! $this->citaEnFuturo($agenda)) {
            return false;
        }
        $accion = $agenda->estado === 'confirmada' ? 'confirmada' : 'pendiente';

        try {
            Mail::to($correo)->send(new NotificacionCitaMail(
                $agenda,
                $accion,
                $this->nombreLavadero(),
                $this->esPrimeraCita($agenda)
            ));

            return true;
        } catch (Throwable $error) {
            report($error);

            return false;
        }
    }

    public function enviarEstado(Agenda $agenda, string $estado): bool
    {
        if ($estado === 'pendiente') {
            return $this->enviarSolicitud($agenda);
        }

        $agenda->loadMissing(['cliente', 'agendaServicios.vehiculo.tipoVehiculo', 'agendaServicios.servicio']);
        $correo = trim((string) $agenda->cliente?->emailCliente);
        if ($correo === '' || ! $this->citaEnFuturo($agenda)) {
            return false;
        }

        try {
            Mail::to($correo)->send(new NotificacionCitaMail(
                $agenda,
                $estado,
                $this->nombreLavadero(),
                $this->esPrimeraCita($agenda)
            ));

            return true;
        } catch (Throwable $error) {
            report($error);

            return false;
        }
    }

    private function citaEnFuturo(Agenda $agenda): bool
    {
        return Carbon::parse($agenda->fecha.' '.$agenda->hora)->isFuture();
    }

    private function nombreLavadero(): string
    {
        return trim((string) $this->configuracionLavadero->obtener()->nombre)
            ?: config('lavadero.nombre', 'LavaSoft');
    }

    private function esPrimeraCita(Agenda $agenda): bool
    {
        return ! Agenda::query()
            ->where('clienteId', $agenda->clienteId)
            ->where('id', '<', $agenda->getKey())
            ->exists();
    }
}
