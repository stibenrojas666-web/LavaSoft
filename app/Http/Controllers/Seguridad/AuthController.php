<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Seguridad\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function showLoginForm(): View
    {
        return view('seguridad.login');
    }

    public function login(LoginRequest $inicioSesionRequest): RedirectResponse
    {
        $autenticado = $this->authService->autenticar(
            $inicioSesionRequest->validated(),
            $inicioSesionRequest->boolean('remember')
        );

        if (! $autenticado) {
            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->onlyInput('email');
        }

                $inicioSesionRequest->session()->regenerate();

        $destino = $this->authService->destinoInicial($inicioSesionRequest->user());

        if ($destino === null) {
            $this->authService->cerrarSesion();
            $inicioSesionRequest->session()->invalidate();
            $inicioSesionRequest->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Tu usuario no tiene permisos asignados. Habla con el administrador.'])
                ->onlyInput('email');
        }

        return redirect()->intended($destino);
    }

    public function logout(Request $cierreSesionRequest): RedirectResponse
    {
        $this->authService->cerrarSesion();

        $cierreSesionRequest->session()->invalidate();
        $cierreSesionRequest->session()->regenerateToken();

        return redirect()->route('login');
    }

}
