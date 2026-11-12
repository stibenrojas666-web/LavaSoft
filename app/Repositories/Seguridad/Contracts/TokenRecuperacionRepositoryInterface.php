<?php

namespace App\Repositories\Seguridad\Contracts;

interface TokenRecuperacionRepositoryInterface
{
    public function buscarPorCorreo(string $correo): ?object;

    public function guardar(string $correo, string $token, mixed $creadoEn): void;

    public function eliminarPorCorreo(string $correo): void;
}
