<?php

namespace App\Services;

use App\Repositories\PagoRepository;
use Illuminate\Support\Facades\DB;

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
            $pago = $this->pagoRepository->keep($datos);
            $this->pagoRepository->confirmarAgenda((int) $pago->agendaId);

            return $pago;
        });
    }

    public function findById(int $id)
    {
        return $this->pagoRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return DB::transaction(function () use ($id, $datos) {
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
