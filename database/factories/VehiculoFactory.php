<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\TipoVehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    protected $model = \App\Models\Vehiculo::class;

    public function definition(): array
    {
        return [
            'clienteID' => Cliente::factory(),
            'tipoVehiculoId' => TipoVehiculo::query()->inRandomOrder()->value('id') ?? TipoVehiculo::factory(),
            'placa' => strtoupper(fake()->unique()->bothify('???####')),
            'modelo' => fake()->word(),
            'color' => fake()->safeColorName(),
            'estado' => 'Activo',
        ];
    }
}
