<?php

namespace App\Services;

use App\Repositories\Contracts\ClienteRepositoryInterface as ClienteRepository;

class ClienteService {
    private ClienteRepository $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarTodo(){
        return $this->clienteRepository->listarTodo();
    }

    public function buscar(string $texto)
    {
        return $this->clienteRepository->buscar($texto);
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));

        return ['clientes' => $this->buscar($buscar), 'buscar' => $buscar];
    }
     public function guardar(array $datos){
        return $this->clienteRepository->guardar($datos);
}

public function eliminar(int $id): bool
{
    if ($this->clienteRepository->tieneDependencias($id)) {
        return false;
    }

    return $this->clienteRepository->eliminar($id) > 0;
}

    public function buscarPorId(int $id){
        return $this->clienteRepository->buscarPorId($id);
    }

    public function buscarConHistorial(int $id)
    {
        return $this->clienteRepository->buscarConHistorial($id);
    }

    public function actualizar (int $id,array $datos){
        return $this ->clienteRepository->actualizar ($id, $datos);
    }
}
