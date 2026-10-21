<?php

namespace App\Console\Commands\Seguridad;

use App\Services\Seguridad\PermisoService;
use Illuminate\Console\Command;

class CrearModulo extends Command
{
    protected $signature = 'seguridad:modulo {nombres* : Uno o varios módulos, por ejemplo clientes proveedores}';

    protected $description = 'Crea los permisos estándar de uno o varios módulos y se los da al rol administrador';

    public function handle(PermisoService $permisoService): int
    {
        foreach ($this->argument('nombres') as $nombre) {
            $total = $permisoService->crearModulo($nombre);

            $this->info('Módulo "' . $nombre . '": ' . $total . ' permisos listos.');
        }

        return self::SUCCESS;
    }
}
