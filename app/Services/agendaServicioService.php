<?php

namespace App\Services;

use App\Repositories\Contracts\AgendaServicioRepositoryInterface as AgendaServicioRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgendaServicioService
{
    private AgendaServicioRepository $agendaServicioRepository;
    private AgendaService $agendaService;

    public function __construct(
        AgendaServicioRepository $agendaServicioRepository,
        AgendaService $agendaService
    ) {
        $this->agendaServicioRepository = $agendaServicioRepository;
        $this->agendaService = $agendaService;
    }

    public function getAll()
    {
        return $this->agendaServicioRepository->getAll();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return $this->agendaServicioRepository->buscar($texto, $filtros);
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado', 'desde', 'hasta']));

        return [
            'agendaServicio' => $this->buscar($buscar, $filtros),
            'buscar' => $buscar,
            'filtros' => $filtros,
        ];
    }

    public function keep(array $datos)
    {
        return DB::transaction(function () use ($datos) {

            $agendaId = (int) $datos['agendaId'];

            $this->validarAgendaModificable($agendaId);

            $agenda = $this->agendaService->findById($agendaId);

            $this->validarVehiculoDelCliente(
                (int) $datos['vehiculoId'],
                (int) $agenda->clienteId
            );

            $datos['Precio'] = $this->agendaService->calcularPrecioServicio(
                (int) $datos['vehiculoId'],
                (int) $datos['servicioId'],
                $datos['Precio'] ?? null
            );

            $datos['duracionMinutos'] = $this->agendaService->calcularDuracionServicio(
                (int) $datos['vehiculoId'],
                (int) $datos['servicioId']
            );

            $lineas = $this->obtenerLineasActuales($agenda);

            $lineas[] = [
                'vehiculoId' => (int) $datos['vehiculoId'],
                'servicioId' => (int) $datos['servicioId'],
            ];

            $this->agendaService->validarDisponibilidadDeServicios(
                $agendaId,
                $lineas
            );

            return $this->agendaServicioRepository->keep($datos);
        });
    }

    public function findById(int $id)
    {
        return $this->agendaServicioRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return DB::transaction(function () use ($id, $datos) {

            $actual = $this->agendaServicioRepository->findById($id);

            $agendaAnteriorId = (int) $actual->agendaId;
            $nuevaAgendaId = (int) $datos['agendaId'];

            $this->validarAgendaModificable($agendaAnteriorId);
            $this->validarAgendaModificable($nuevaAgendaId);

            $agenda = $this->agendaService->findById($nuevaAgendaId);

            $this->validarVehiculoDelCliente(
                (int) $datos['vehiculoId'],
                (int) $agenda->clienteId
            );

            $datos['Precio'] = $this->agendaService->calcularPrecioServicio(
                (int) $datos['vehiculoId'],
                (int) $datos['servicioId'],
                $datos['Precio'] ?? null
            );

            $datos['duracionMinutos'] = $this->agendaService->calcularDuracionServicio(
                (int) $datos['vehiculoId'],
                (int) $datos['servicioId']
            );

            $lineas = $this->obtenerLineasActuales($agenda, $id);

            $lineas[] = [
                'vehiculoId' => (int) $datos['vehiculoId'],
                'servicioId' => (int) $datos['servicioId'],
            ];

            $this->agendaService->validarDisponibilidadDeServicios(
                $nuevaAgendaId,
                $lineas
            );

            return $this->agendaServicioRepository->update($id, $datos);
        });
    }

    public function delete(int $id)
    {
        $actual = $this->agendaServicioRepository->findById($id);

        $this->validarAgendaModificable((int) $actual->agendaId);

        return $this->agendaServicioRepository->delete($id);
    }

    /**
     * Se obtiene los servicios actuales de una cita.
     * Se permite excluir una línea cuando se está editando.
     */
    private function obtenerLineasActuales($agenda, ?int $excluirId = null): array
    {
        $lineas = [];

        foreach ($agenda->agendaServicios as $linea) {

            if ($excluirId !== null && (int) $linea->id === $excluirId) {
                continue;
            }

            $lineas[] = [
                'vehiculoId' => (int) $linea->vehiculoId,
                'servicioId' => (int) $linea->servicioId,
            ];
        }

        return $lineas;
    }

    private function validarAgendaModificable(int $agendaId): void
    {
        $agenda = $this->agendaService->findById($agendaId);

        if ($agenda->estado === 'terminada') {
            throw ValidationException::withMessages([
                'agendaId' => 'No se pueden modificar los servicios de una cita terminada.',
            ]);
        }

        if ($this->agendaServicioRepository->agendaTienePago($agendaId)) {
            throw ValidationException::withMessages([
                'agendaId' => 'No se pueden modificar los servicios de una cita que ya tiene un pago registrado.',
            ]);
        }
    }

    private function validarVehiculoDelCliente(int $vehiculoId, int $clienteId): void
    {
        if (!$this->agendaService->vehiculoPerteneceACliente($vehiculoId, $clienteId)) {
            throw ValidationException::withMessages([
                'vehiculoId' => 'El vehículo seleccionado no pertenece al cliente de esta cita.',
            ]);
        }
    }
}
