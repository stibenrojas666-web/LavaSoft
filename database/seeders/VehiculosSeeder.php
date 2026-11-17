<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class VehiculosSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = Cliente::where('emailCliente', 'cliente.demo@lavasoft.test')->firstOrFail();
        $tipo = TipoVehiculo::where('nombre', 'carro')->firstOrFail();

        Vehiculo::firstOrCreate(['placa' => 'DEMO001'], [
            'clienteID' => $cliente->id,
            'tipoVehiculoId' => $tipo->id,
            'modelo' => 'Vehículo de prueba',
            'color' => 'Blanco',
            'estado' => 'Activo',
        ]);

        foreach ([2 => 'moto', 3 => 'turbo', 4 => 'carro'] as $numero => $tipoNombre) {
            $tipoVehiculo = TipoVehiculo::where('nombre', $tipoNombre)->firstOrFail();

            Vehiculo::firstOrCreate(
                ['placa' => sprintf('DEMO%03d', $numero)],
                Vehiculo::factory()->make([
                    'clienteID' => $cliente->id,
                    'tipoVehiculoId' => $tipoVehiculo->id,
                    'placa' => sprintf('DEMO%03d', $numero),
                ])->getAttributes()
            );
        }
    }
}
