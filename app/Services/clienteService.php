<?php

namespace App\Services;

use App\Repositories\Contracts\ClienteRepositoryInterface as ClienteRepository;

class ClienteService
{
    private ClienteRepository $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarTodo()
    {
        return $this->clienteRepository->listarTodo();
    }

    public function buscar(string $texto, bool $archivados = false)
    {
        return $this->clienteRepository->buscar($texto, $archivados);
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $archivados = filter_var($datos['archivados'] ?? false, FILTER_VALIDATE_BOOLEAN);

        return ['clientes' => $this->buscar($buscar, $archivados), 'buscar' => $buscar, 'archivados' => $archivados];
    }

    public function guardar(array $datos)
    {
        return $this->clienteRepository->guardar($datos);
    }

    public function eliminar(int $id): bool
    {
        return $this->clienteRepository->eliminar($id) > 0;
    }

    public function archivar(int $id): bool
    {
        return $this->clienteRepository->archivar($id) > 0;
    }

    public function reactivar(int $id): bool
    {
        return $this->clienteRepository->reactivar($id) > 0;
    }

    public function eliminarTodo(int $id): bool
    {
        return $this->clienteRepository->eliminarTodo($id);
    }

    public function buscarPorId(int $id)
    {
        return $this->clienteRepository->buscarPorId($id);
    }

    public function buscarConHistorial(int $id)
    {
        return $this->clienteRepository->buscarConHistorial($id);
    }

    public function actualizar(int $id, array $datos)
    {
        return $this->clienteRepository->actualizar($id, $datos);
    }
}
