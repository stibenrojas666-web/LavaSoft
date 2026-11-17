<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Repositories\Contracts\AgendaRepositoryInterface;
use App\Repositories\Contracts\AgendaServicioRepositoryInterface;
use App\Repositories\Contracts\PrecioServicioRepositoryInterface;
use App\Repositories\Contracts\VehiculoRepositoryInterface;
use App\Services\AgendaService;
use App\Services\ClienteService;
use App\Services\EmpleadoService;
use App\Services\ServicioService;
use App\Services\VehiculoService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class AgendaServiceEstadoTest extends TestCase
{
    public function test_no_permite_marcar_una_cita_terminada_desde_el_cambio_rapido(): void
    {
        $agendaRepository = Mockery::mock(AgendaRepositoryInterface::class);
        $agendaRepository->shouldNotReceive('findById');

        $this->expectException(ValidationException::class);

        $this->crearServicio($agendaRepository)->cambiarEstadoRapido(27, 'terminada');
    }

    public function test_no_permite_cancelar_una_cita_que_ya_tiene_pago(): void
    {
        $agenda = Mockery::mock(Agenda::class)->makePartial();
        $agenda->setRawAttributes([
            'estado' => 'confirmada',
            'fecha' => Carbon::tomorrow()->toDateString(),
            'hora' => '10:00',
        ], true);
        $agenda->shouldReceive('pago')->once()->andReturn(new class
        {
            public function exists(): bool
            {
                return true;
            }
        });
        $agenda->shouldNotReceive('update');

        $agendaRepository = Mockery::mock(AgendaRepositoryInterface::class);
        $agendaRepository->shouldReceive('findById')->once()->with(27)->andReturn($agenda);

        $this->expectException(ValidationException::class);

        $this->crearServicio($agendaRepository)->cambiarEstadoRapido(27, 'cancelada');
    }

    public function test_permite_confirmar_una_cita_futura(): void
    {
        $agenda = Mockery::mock(Agenda::class)->makePartial();
        $agenda->setRawAttributes([
            'estado' => 'pendiente',
            'fecha' => Carbon::tomorrow()->toDateString(),
            'hora' => '10:00',
        ], true);
        $agenda->shouldReceive('update')->once()->with(['estado' => 'confirmada'])->andReturnTrue();

        $agendaRepository = Mockery::mock(AgendaRepositoryInterface::class);
        $agendaRepository->shouldReceive('findById')->once()->with(27)->andReturn($agenda);

        $this->crearServicio($agendaRepository)->cambiarEstadoRapido(27, 'confirmada');
    }

    private function crearServicio(AgendaRepositoryInterface $agendaRepository): AgendaService
    {
        return new AgendaService(
            $agendaRepository,
            Mockery::mock(AgendaServicioRepositoryInterface::class),
            Mockery::mock(PrecioServicioRepositoryInterface::class),
            Mockery::mock(VehiculoRepositoryInterface::class),
            Mockery::mock(ClienteService::class),
            Mockery::mock(EmpleadoService::class),
            Mockery::mock(VehiculoService::class),
            Mockery::mock(ServicioService::class),
        );
    }
}
