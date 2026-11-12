<?php

namespace App\Services\Seguridad;

use App\Repositories\Seguridad\Contracts\UsuarioRepositoryInterface as UsuarioRepository;
use App\Repositories\Seguridad\Contracts\TokenRecuperacionRepositoryInterface as TokenRecuperacionRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class RecuperacionContrasenaService
{
    private const VIGENCIA_MINUTOS = 10;
    private const MAX_INTENTOS = 5;
    private const ESPERA_REENVIO = 60;

    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private TokenRecuperacionRepository $tokenRepository
    )
    {
    }

    public function solicitarCodigo(string $email, string $ip): string
    {
        $email = mb_strtolower(trim($email));
        $clave = 'recuperacion:'.$ip.':'.$email;

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            return 'limite';
        }

        RateLimiter::hit($clave, 3600);
        RateLimiter::clear('verificar-codigo:'.$ip.':'.$email);

        if ($this->usuarioRepository->buscarPorEmail($email) === null) {
            return 'enviado';
        }

        return $this->generarYEnviarCodigo($email) ? 'enviado' : 'error_envio';
    }

    public function reenviarCodigo(string $email, string $ip): string
    {
        $claveEspera = 'reenviar:'.$ip.':'.$email;
        $claveLimite = 'reenviar-limite:'.$email;

        if (RateLimiter::tooManyAttempts($claveEspera, 1)) {
            return 'esperar';
        }

        if (RateLimiter::tooManyAttempts($claveLimite, 5)) {
            return 'limite';
        }

        RateLimiter::hit($claveEspera, self::ESPERA_REENVIO);
        RateLimiter::hit($claveLimite, 3600);

        return $this->generarYEnviarCodigo($email) ? 'enviado' : 'error_envio';
    }

    public function verificarCodigo(string $email, string $codigo, string $ip): string
    {
        $clave = 'verificar-codigo:'.$ip.':'.$email;

        if (RateLimiter::tooManyAttempts($clave, self::MAX_INTENTOS)) {
            return 'limite';
        }

        $registro = $this->tokenRepository->buscarPorCorreo($email);
        $usuarioExiste = $this->usuarioRepository->buscarPorEmail($email) !== null;
        $codigoVencido = ! $registro
            || ! $registro->created_at
            || now()->greaterThan(
                \Illuminate\Support\Carbon::parse($registro->created_at)
                    ->addMinutes(self::VIGENCIA_MINUTOS)
            );

        if ($codigoVencido || ! $usuarioExiste || ! Hash::check($codigo, $registro->token)) {
            RateLimiter::hit($clave, 600);

            return 'invalido';
        }

        $this->tokenRepository->eliminarPorCorreo($email);
        RateLimiter::clear($clave);

        return 'verificado';
    }

    public function verificacionVigente(bool $verificado, ?int $verificadoEn, bool $tieneEmail): bool
    {
        return $verificado
            && $verificadoEn !== null
            && now()->timestamp <= $verificadoEn + self::VIGENCIA_MINUTOS * 60
            && $tieneEmail;
    }

    public function actualizarContrasena(string $email, string $contrasena): bool
    {
        $usuario = $this->usuarioRepository->buscarPorEmail($email);

        if ($usuario === null) {
            return false;
        }

        $usuario->password = Hash::make($contrasena);
        $usuario->save();

        try {
            Mail::raw(
                "Hola {$usuario->name},\n\n"
                ."La contraseña de tu cuenta de LavaSoft fue cambiada correctamente.\n\n"
                .'Si no realizaste este cambio, comunícate con el administrador.',
                fn ($mensaje) => $mensaje->to($email)->subject('Contraseña actualizada - LavaSoft')
            );
        } catch (Throwable $error) {
            report($error);
        }

        return true;
    }

    private function generarYEnviarCodigo(string $email): bool
    {
        if ($this->usuarioRepository->buscarPorEmail($email) === null) {
            return true;
        }

        $codigo = (string) random_int(100000, 999999);

        $this->tokenRepository->guardar($email, Hash::make($codigo), now());

        try {
            Mail::raw(
                "Tu código de recuperación de LavaSoft es: {$codigo}\n\n"
                .'Este código vence en 10 minutos y solo puede utilizarse una vez.\n'
                .'Si no solicitaste este cambio, ignora este mensaje.',
                fn ($mensaje) => $mensaje->to($email)->subject('Código de recuperación - LavaSoft')
            );
        } catch (Throwable $error) {
            $this->tokenRepository->eliminarPorCorreo($email);
            report($error);

            return false;
        }

        return true;
    }
}
