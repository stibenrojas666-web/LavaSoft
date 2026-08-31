<?php

namespace App\Services;

use App\Repositories\EmpleadoRepository;

class EmpleadoService
{
    private EmpleadoRepository $empleadoRepository;

    public function __construct(EmpleadoRepository $empleadoRepository)
    {
        $this->empleadoRepository = $empleadoRepository;
    }

    public function listarTodo()
    {
        return $this->empleadoRepository->listarTodo();
    }

    public function guardar(array $datos)
    {
        return $this->empleadoRepository->guardar($datos);
    }

    public function eliminar(int $id)
    {
        $this->empleadoRepository->eliminar($id);
    }

    public function buscarporId(int $id)
    {
        return $this->empleadoRepository->buscarporId($id);
    }

    public function actualizar(int $id, array $datos)
    {
        return $this->empleadoRepository->actualizar($id, $datos);
    }


}

