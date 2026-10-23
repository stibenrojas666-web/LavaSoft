<?php

namespace App\Repositories;

use App\Models\ConfiguracionLavadero;

class ConfiguracionLavaderoRepository
{
    public function obtener(): ConfiguracionLavadero
    {
        return ConfiguracionLavadero::query()->first()
            ?? new ConfiguracionLavadero(['nombre' => 'LavaSoft', 'mostrar_logo' => true]);
    }

    public function guardar(array $datos): ConfiguracionLavadero
    {
        $configuracion = ConfiguracionLavadero::query()->firstOrNew(['id' => 1]);
        $configuracion->fill($datos);
        $configuracion->save();

        return $configuracion;
    }
}
