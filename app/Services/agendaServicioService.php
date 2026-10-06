<?php

namespace App\Services;

use App\Repositories\AgendaServicioRepository;

class AgendaServicioService
{
    private AgendaServicioRepository $agendaServicioRepository;

    public function __construct(AgendaServicioRepository $agendaServicioRepository)
    {
        $this->agendaServicioRepository = $agendaServicioRepository;
    }

    public function getAll()
    {
        return $this->agendaServicioRepository->getAll();
    }

    public function keep(array $datos)
    {
        return $this->agendaServicioRepository->keep($datos);
    }

    public function findById(int $id)
    {
        return $this->agendaServicioRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return $this->agendaServicioRepository->update($id, $datos);
    }

    public function delete(int $id)
    {
        return $this->agendaServicioRepository->delete($id);
    }
}