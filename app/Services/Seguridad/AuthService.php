<?php

namespace App\Services\Seguridad;

use App\Repositories\Seguridad\Contracts\UsuarioRepositoryInterface as UsuarioRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService
{
    public function __construct(private UsuarioRepository $usuarioRepository)
    {
    }

    public function autenticar(array $credenciales, bool $recordar = false): bool
    {
        $usuario = $this->usuarioRepository->buscarPorEmail($credenciales['email']);

        if ($usuario === null || ! Hash::check($credenciales['password'], $usuario->getAuthPassword())) {
            return false;
        }

        Auth::guard(config('seguridad.guard'))->login($usuario, $recordar);

        return true;
    }

    public function cerrarSesion(): void
    {
        Auth::guard(config('seguridad.guard'))->logout();
    }

    public function destinoInicial(Authenticatable $usuario): ?string
    {
        foreach ([
            'ver dashboard' => 'dashboard.index',
            'ver agenda' => 'agenda.index',
            'ver pagos' => 'pagos.historial',
            'ver clientes' => 'clientes.index',
            'ver vehiculos' => 'vehiculos.index',
            'ver servicios' => 'servicios.index',
        ] as $permiso => $ruta) {
            if ($usuario->can($permiso)) {
                return route($ruta);
            }
        }

        return null;
    }
}
