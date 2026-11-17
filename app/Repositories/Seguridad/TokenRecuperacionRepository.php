<?php

namespace App\Repositories\Seguridad;

use App\Repositories\Seguridad\Contracts\TokenRecuperacionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TokenRecuperacionRepository implements TokenRecuperacionRepositoryInterface
{
    public function buscarPorCorreo(string $correo): ?object
    {
        return DB::table('password_reset_tokens')->where('email', $correo)->first();
    }

    public function guardar(string $correo, string $token, mixed $creadoEn): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $correo],
            ['token' => $token, 'created_at' => $creadoEn]
        );
    }

    public function eliminarPorCorreo(string $correo): void
    {
        DB::table('password_reset_tokens')->where('email', $correo)->delete();
    }
}
