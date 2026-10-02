<?php

namespace App\Services;

use App\Repositories\AgendaRepository;

class AgendaService
{
    private AgendaRepository $agendaRepository;

    public function __construct(AgendaRepository $agendaRepository)
    {
        $this->agendaRepository = $agendaRepository;
    }

    public function getAll()
    {
        return $this->agendaRepository->getAll();
    }

    public function keep(array $datos)
    {
        return $this->agendaRepository->keep($datos);
    }

    public function findById(int $id)
    {
        return $this->agendaRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return $this->agendaRepository->update($id, $datos);
    }

    public function delete(int $id)
    {
        return $this->agendaRepository->delete($id);
    }
}