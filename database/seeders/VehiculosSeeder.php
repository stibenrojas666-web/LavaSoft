<?php

namespace Database\Seeders;

use App\Models\clientes;
use App\Models\tipoVehiculo;
use App\Models\vehiculo;
use Illuminate\Database\Seeder;

class VehiculosSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = clientes::where('emailCliente', 'cliente.demo@lavasoft.test')->firstOrFail();
        $tipo = tipoVehiculo::where('nombre', 'carro')->firstOrFail();

        vehiculo::firstOrCreate(['placa' => 'DEMO001'], [
            'clienteID' => $cliente->id,
            'tipoVehiculoId' => $tipo->id,
            'modelo' => 'Vehículo de prueba',
            'color' => 'Blanco',
            'estado' => 'Activo',
        ]);

        foreach ([2 => 'moto', 3 => 'turbo', 4 => 'carro'] as $numero => $tipoNombre) {
            $tipoVehiculo = tipoVehiculo::where('nombre', $tipoNombre)->firstOrFail();

            vehiculo::firstOrCreate(
                ['placa' => sprintf('DEMO%03d', $numero)],
                vehiculo::factory()->make([
                    'clienteID' => $cliente->id,
                    'tipoVehiculoId' => $tipoVehiculo->id,
                    'placa' => sprintf('DEMO%03d', $numero),
                ])->getAttributes()
            );
        }
    }
}
