<?php

namespace App\Services;

use App\Repositories\AgendaRepository;
use App\Repositories\AgendaServicioRepository;
use App\Repositories\precioServicioRepository;
use App\Repositories\VehiculoRespository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgendaService
{
    private AgendaRepository $agendaRepository;
    private AgendaServicioRepository $agendaServicioRepository;
    private precioServicioRepository $precioServicioRepository;
    private VehiculoRespository $vehiculoRepository;

    public function __construct(
        AgendaRepository $agendaRepository,
        AgendaServicioRepository $agendaServicioRepository,
        precioServicioRepository $precioServicioRepository,
        VehiculoRespository $vehiculoRepository
    ) {
        $this->agendaRepository = $agendaRepository;
        $this->agendaServicioRepository = $agendaServicioRepository;
        $this->precioServicioRepository = $precioServicioRepository;
        $this->vehiculoRepository = $vehiculoRepository;
    }

    public function getAll()
    {
        return $this->agendaRepository->getAll();
    }

    public function keep(array $datos)
    {
        $servicios = $datos['servicios'];
        $preciosAcordados = $datos['precios_acordados'] ?? [];
        unset($datos['servicios'], $datos['precios_acordados']);

        return DB::transaction(function () use ($datos, $servicios, $preciosAcordados) {
            $agenda = $this->agendaRepository->keep($datos);

            $tipoVehiculoId = $this->vehiculoRepository
                ->finById($agenda->vehiculoId)
                ->tipoVehiculoId;

            foreach ($servicios as $servicioId) {
                $configuracionPrecio = $this->precioServicioRepository->buscarConfiguracion(
                    (int) $servicioId,
                    (int) $tipoVehiculoId
                );

                if ($configuracionPrecio === null) {
                    throw ValidationException::withMessages([
                        'servicios' => 'Uno de los servicios no tiene precio para ese tipo de vehículo.',
                    ]);
                }

                if ($configuracionPrecio->modalidad === 'convenir') {
                    $precio = $preciosAcordados[$servicioId] ?? null;
                    if (!is_numeric($precio) || (float) $precio < 0.01 || (float) $precio > 99999999.99) {
                        throw ValidationException::withMessages([
                            "precios_acordados.{$servicioId}" => 'Escribe el precio acordado con el cliente.',
                        ]);
                    }
                } else {
                    $precio = $configuracionPrecio->precio;
                    if ($precio === null) {
                        throw ValidationException::withMessages([
                            'servicios' => 'Uno de los servicios no tiene un precio base configurado.',
                        ]);
                    }
                }

                $this->agendaServicioRepository->keep([
                    'agendaId' => $agenda->id,
                    'servicioId' => $servicioId,
                    'Precio' => $precio,
                ]);
            }

            return $agenda;
        });
    }

    public function findById(int $id)
    {
        return $this->agendaRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        return $this->agendaRepository->update($id, $datos);
    }

    public function delete(int $id): bool
    {
        if ($this->agendaRepository->tieneDependencias($id)) {
            return false;
        }

        return $this->agendaRepository->delete($id) > 0;
    }
}
