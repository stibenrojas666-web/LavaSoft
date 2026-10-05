<?php

namespace App\Services;

use App\Repositories\PagoRepository;

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
        return $this->pagoRepository->keep($datos);
    }

    public function findById(int $id)
    {
        return $this->pagoRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return $this->pagoRepository->update($id, $datos);
    }

    public function delete(int $id)
    {
        return $this->pagoRepository->delete($id);
    }
}