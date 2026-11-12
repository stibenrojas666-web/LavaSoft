<?php

namespace App\Services;

use App\Repositories\Contracts\VehiculoRepositoryInterface;
use Illuminate\Validation\ValidationException;

class VehiculoService
{
    public function __construct(private VehiculoRepositoryInterface $vehiculoRepository)
    {
    }

    public function getAll()
    {
        return $this->vehiculoRepository->getAll();
    }

    public function listarParaAgenda(array $incluirIds = [])
    {
        $idsIncluidos = array_map('intval', $incluirIds);

        return $this->getAll()->filter(fn ($vehiculo) =>
            strtolower((string) $vehiculo->estado) === 'activo'
            || in_array((int) $vehiculo->id, $idsIncluidos, true)
        )->values();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return $this->vehiculoRepository->buscar($texto, $filtros);
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado', 'tipoVehiculoId']));

        return [
            'vehiculo' => $this->buscar($buscar, $filtros),
            'buscar' => $buscar,
            'filtros' => $filtros,
        ];
    }

    public function cambiarEstado(int $id): bool
    {
        return $this->vehiculoRepository->cambiarEstado($id);
    }

    public function keep(array $datos)
    {
        return $this->vehiculoRepository->keep($datos);
    }

    public function findById(int $id)
    {
        return $this->vehiculoRepository->finById($id);
    }

    public function historial(int $id): array
    {
        $vehiculo = $this->vehiculoRepository->finById($id);
        $historial = $this->vehiculoRepository->historialServicios($id);

        return compact('vehiculo', 'historial');
    }

    public function update(int $id, array $datos)
    {
        $actual = $this->vehiculoRepository->finById($id);
        if ((int) $actual->clienteID !== (int) $datos['clienteID'] && $this->vehiculoRepository->tieneDependencias($id)) {
            throw ValidationException::withMessages([
                'clienteID' => 'No puedes cambiar el dueño de un vehículo que ya tiene citas.',
            ]);
        }

        return $this->vehiculoRepository->update($id, $datos);
    }

    public function delete(int $id): bool
    {
        if ($this->vehiculoRepository->tieneDependencias($id)) {
            return false;
        }

        return $this->vehiculoRepository->delete($id) > 0;
    }
}
