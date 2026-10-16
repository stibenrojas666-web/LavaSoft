<?php

namespace Database\Seeders;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\clientes;
use App\Models\servicio;
use App\Models\vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AgendaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = clientes::where('emailCliente', 'cliente.demo@lavasoft.test')->firstOrFail();
        $vehiculo = vehiculo::where('placa', 'DEMO001')->firstOrFail();

        DB::transaction(function () use ($cliente, $vehiculo) {
            $cita = agenda::firstOrCreate([
                'clienteId' => $cliente->id,
                'vehiculoId' => $vehiculo->id,
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
                'agendaId' => $cita->id,
                'servicioId' => $servicio->id,
            ], ['Precio' => 85000]);
        });
    }
}
