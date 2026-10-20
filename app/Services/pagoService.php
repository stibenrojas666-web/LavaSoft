<?php

namespace App\Services;

use App\Repositories\PagoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoService
{
    private PagoRepository $pagoRepository;

    public function __construct(PagoRepository $pagoRepository)
    {
        $this->pagoRepository = $pagoRepository;
    }

    public function getAll()
    {
        return $this->pagoRepository->getAll();
    }

    public function keep(array $datos)
    {
        return DB::transaction(function () use ($datos) {
            $agenda = $this->pagoRepository->agendaDisponibleParaPago((int) $datos['agendaId'], true);

            if (!$agenda) {
                throw ValidationException::withMessages([
                    'agendaId' => 'Esta cita ya tiene un pago registrado o no está disponible.',
                ]);
            }

            $datos['Monto'] = $this->totalValido((int) $agenda->id);

            $pago = $this->pagoRepository->keep($datos);
            $this->pagoRepository->confirmarAgenda((int) $pago->agendaId);

            return $pago;
        });
    }

    public function agendasSinPago()
    {
        return $this->pagoRepository->agendasSinPago();
    }

    /** Prepara las citas, la selección y el total para el formulario de pago. */
    public function datosParaCrear(?int $agendaId = null): array
    {
        $agenda = $this->agendasSinPago();
        $agendaSeleccionada = null;
        $total = null;

        if ($agendaId !== null) {
            $agendaSeleccionada = $this->agendaDisponibleParaPago($agendaId);
            $total = $this->calcularTotal((int) $agendaSeleccionada->id);
        }

        return compact('agenda', 'agendaSeleccionada', 'total');
    }

    public function agendaDisponibleParaPago(int $agendaId)
    {
        $agenda = $this->pagoRepository->agendaDisponibleParaPago($agendaId);

        if (!$agenda) {
            throw ValidationException::withMessages([
                'agendaId' => 'Esta cita ya tiene un pago registrado o no está disponible.',
            ]);
        }

        return $agenda;
    }

    public function calcularTotal(int $agendaId): float
    {
        return $this->pagoRepository->totalServiciosDeAgenda($agendaId);
    }

    private function totalValido(int $agendaId): float
    {
        $total = $this->calcularTotal($agendaId);

        if ($total <= 0 || $total > 99999999.99) {
            throw ValidationException::withMessages([
                'agendaId' => 'El total debe ser mayor que cero y no puede superar el límite permitido para un pago.',
            ]);
        }

        return $total;
    }

    public function findById(int $id)
    {
        return $this->pagoRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return DB::transaction(function () use ($id, $datos) {
            $pagoExistente = $this->pagoRepository->findById($id);
            $datos['Monto'] = $this->totalValido((int) $pagoExistente->agendaId);

            unset($datos['agendaId']);
            $pago = $this->pagoRepository->update($id, $datos);
            $this->pagoRepository->confirmarAgenda((int) $pago->agendaId);

            return $pago;
        });
    }

    public function delete(int $id)
    {
        return $this->pagoRepository->delete($id);
    }
}
