<?php

namespace App\Services;

use App\Repositories\AgendaServicioRepository;
use Illuminate\Validation\ValidationException;

class AgendaServicioService
{
    private AgendaServicioRepository $agendaServicioRepository;
    private AgendaService $agendaService;

    public function __construct(AgendaServicioRepository $agendaServicioRepository, AgendaService $agendaService)
    {
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

    public function keep(array $datos)
    {
        $this->validarAgendaSinPago((int) $datos['agendaId']);
        $agenda = $this->agendaServicioRepository->buscarAgenda((int) $datos['agendaId']);
        $this->validarVehiculoDelCliente((int) $datos['vehiculoId'], (int) $agenda->clienteId);
        $this->agendaService->validarVehiculoDisponible(
            (int) $datos['vehiculoId'],
            (string) $agenda->fecha,
            (string) $agenda->hora,
            (int) $agenda->id
        );
        $datos['Precio'] = $this->agendaService->calcularPrecioServicio(
            (int) $datos['vehiculoId'],
            (int) $datos['servicioId'],
            $datos['Precio'] ?? null
        );

        return $this->agendaServicioRepository->keep($datos);
    }

    public function findById(int $id)
    {
        return $this->agendaServicioRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        $actual = $this->agendaServicioRepository->findById($id);
        $this->validarAgendaSinPago((int) $actual->agendaId);
        $this->validarAgendaSinPago((int) $datos['agendaId']);
        $agenda = $this->agendaServicioRepository->buscarAgenda((int) $datos['agendaId']);
        $this->validarVehiculoDelCliente((int) $datos['vehiculoId'], (int) $agenda->clienteId);
        $this->agendaService->validarVehiculoDisponible(
            (int) $datos['vehiculoId'],
            (string) $agenda->fecha,
            (string) $agenda->hora,
            (int) $agenda->id
        );
        $datos['Precio'] = $this->agendaService->calcularPrecioServicio(
            (int) $datos['vehiculoId'],
            (int) $datos['servicioId'],
            $datos['Precio'] ?? null
        );

        return $this->agendaServicioRepository->update($id, $datos);
    }

    public function delete(int $id)
    {
        $actual = $this->agendaServicioRepository->findById($id);
        $this->validarAgendaSinPago((int) $actual->agendaId);

        return $this->agendaServicioRepository->delete($id);
    }

    private function validarAgendaSinPago(int $agendaId): void
    {
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
