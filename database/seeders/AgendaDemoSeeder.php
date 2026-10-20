<?php

namespace Database\Seeders;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\clientes;
use App\Models\servicio;
use App\Models\precioServicio;
use App\Models\tipoVehiculo;
use App\Models\vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AgendaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = clientes::where('emailCliente', 'cliente.demo@lavasoft.test')->firstOrFail();
        $vehiculoPagado = vehiculo::where('placa', 'DEMO001')->firstOrFail();
        $vehiculoPendiente = vehiculo::where('placa', 'DEMO004')->firstOrFail();

        DB::transaction(function () use ($cliente, $vehiculoPagado, $vehiculoPendiente) {
            $citaPagada = agenda::firstOrCreate([
                'clienteId' => $cliente->id,
                'vehiculoId' => $vehiculoPagado->id,
                'fecha' => Carbon::today()->toDateString(),
                'hora' => '08:00:00',
            ], [
                'empleadoId' => null,
                'estado' => 'pendiente',
                'tipoDeAtencion' => 'Por orden de llegada',
            ]);

            $servicio = servicio::where('nombre', 'Lavada general con todo incluido')->firstOrFail();

            // El catálogo lo marca "a convenir"; este valor de ejemplo es el
            // monto pactado para esta cita de prueba.
            agendaServicios::firstOrCreate([
                'agendaId' => $citaPagada->id,
                'servicioId' => $servicio->id,
            ], ['Precio' => 85000]);

            // Cita pendiente para ensayar el cobro completo desde la pantalla de pagos.
            $citaPendiente = agenda::firstOrCreate([
                'clienteId' => $cliente->id,
                'vehiculoId' => $vehiculoPendiente->id,
                'fecha' => Carbon::tomorrow()->toDateString(),
                'hora' => '10:00:00',
            ], [
                'empleadoId' => null,
                'estado' => 'pendiente',
                'tipoDeAtencion' => 'Por cita agendada',
            ]);

            $tipoVehiculoId = tipoVehiculo::where('nombre', 'carro')->value('id');
            foreach ([
                'Lavada solo por fuera',
                'Lavado de motor en seco',
            ] as $nombreServicio) {
                $servicioPrueba = servicio::where('nombre', $nombreServicio)->firstOrFail();
                $precio = precioServicio::where('servicio_id', $servicioPrueba->id)
                    ->where('tipo_vehiculo_id', $tipoVehiculoId)
                    ->value('precio');

                agendaServicios::firstOrCreate([
                    'agendaId' => $citaPendiente->id,
                    'servicioId' => $servicioPrueba->id,
                ], ['Precio' => $precio]);
            }
        });
    }
}
