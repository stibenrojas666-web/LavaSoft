<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecuperacionCodigoRequest;
use App\Http\Requests\VerificarCodigoRequest;
use App\Http\Requests\ActualizarContrasenaRequest;
use App\Services\Seguridad\RecuperacionContrasenaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecuperacionContrasenaController extends Controller
{
    public function __construct(private RecuperacionContrasenaService $recuperacionService)
    {
    }

    public function mostrarSolicitud(): View
    {
        return view('seguridad.recuperacion.solicitar');
    }

    public function enviarCodigo(RecuperacionCodigoRequest $solicitudCodigoRequest): RedirectResponse
    {
        $email = $solicitudCodigoRequest->validated('email');
        $resultado = $this->recuperacionService->solicitarCodigo($email, $solicitudCodigoRequest->ip());

        if ($resultado === 'limite') {
            return back()->withErrors([
                'email' => 'Has realizado demasiadas solicitudes. Inténtalo más tarde.',
            ])->onlyInput('email');
        }

        $solicitudCodigoRequest->session()->put('recuperacion_email', $email);
        $solicitudCodigoRequest->session()->forget('recuperacion_verificada');

        if ($resultado === 'error_envio') {
            return back()->withErrors([
                'email' => 'No pudimos enviar el correo. Inténtalo nuevamente.',
            ])->withInput();
        }

        return redirect()->route('seguridad.recuperacion.verificar')
            ->with('estado', 'Si el correo está registrado, recibirás un código de verificación.');
    }

    public function mostrarVerificacion(Request $verificacionRequest): View|RedirectResponse
    {
        if (! $verificacionRequest->session()->has('recuperacion_email')) {
            return redirect()->route('seguridad.recuperacion.solicitar');
        }

        return view('seguridad.recuperacion.verificar');
    }

    public function reenviarCodigo(Request $reenviarCodigoRequest): RedirectResponse
    {
        $email = $reenviarCodigoRequest->session()->get('recuperacion_email');

        if (! $email) {
            return redirect()->route('seguridad.recuperacion.solicitar');
        }

        $resultado = $this->recuperacionService->reenviarCodigo($email, $reenviarCodigoRequest->ip());

        if ($resultado === 'esperar') {
            return back()->withErrors([
                'codigo' => 'Debes esperar 60 segundos antes de solicitar otro código.',
            ]);
        }

        if ($resultado === 'limite') {
            return back()->withErrors([
                'codigo' => 'Has alcanzado el límite de reenvíos. Inténtalo más tarde.',
            ]);
        }

        if ($resultado === 'error_envio') {
            return back()->withErrors([
                'codigo' => 'No pudimos enviar el correo. Inténtalo nuevamente.',
            ]);
        }

        $reenviarCodigoRequest->session()->forget('recuperacion_verificada');

        return redirect()->route('seguridad.recuperacion.verificar')
            ->with('estado', 'Si el correo está registrado, recibirás un código de verificación.');
    }

    public function verificarCodigo(VerificarCodigoRequest $verificarCodigoRequest): RedirectResponse
    {
        $email = $verificarCodigoRequest->session()->get('recuperacion_email');

        if (! $email) {
            return redirect()->route('seguridad.recuperacion.solicitar');
        }

        $datos = $verificarCodigoRequest->validated();
        $resultado = $this->recuperacionService->verificarCodigo(
            $email,
            $datos['codigo'],
            $verificarCodigoRequest->ip()
        );

        if ($resultado === 'limite') {
            return back()->withErrors([
                'codigo' => 'Has superado el número de intentos. Solicita un nuevo código más tarde.',
            ]);
        }

        if ($resultado !== 'verificado') {
            return back()->withErrors([
                'codigo' => 'El código es incorrecto o ha vencido.',
            ]);
        }

        $verificarCodigoRequest->session()->put('recuperacion_verificada', true);
        $verificarCodigoRequest->session()->put('recuperacion_verificada_en', now()->timestamp);

        return redirect()->route('seguridad.recuperacion.restablecer');
    }

    public function mostrarRestablecimiento(Request $restablecimientoRequest): View|RedirectResponse
    {
        $sesion = $restablecimientoRequest->session();
        $verificacionVigente = $this->recuperacionService->verificacionVigente(
            (bool) $sesion->get('recuperacion_verificada'),
            $sesion->get('recuperacion_verificada_en'),
            $sesion->has('recuperacion_email')
        );

        if (! $verificacionVigente) {
            $sesion->forget([
                'recuperacion_email',
                'recuperacion_verificada',
                'recuperacion_verificada_en',
            ]);

            return redirect()->route('seguridad.recuperacion.solicitar')
                ->withErrors(['email' => 'La verificación venció. Solicita un nuevo código.']);
        }

        return view('seguridad.recuperacion.restablecer');
    }

    public function actualizarContrasena(ActualizarContrasenaRequest $actualizarContrasenaRequest): RedirectResponse
    {
        $sesion = $actualizarContrasenaRequest->session();
        $email = $sesion->get('recuperacion_email');
        $verificacionVigente = $this->recuperacionService->verificacionVigente(
            (bool) $sesion->get('recuperacion_verificada'),
            $sesion->get('recuperacion_verificada_en'),
            (bool) $email
        );

        if (! $verificacionVigente) {
            $sesion->forget([
                'recuperacion_email',
                'recuperacion_verificada',
                'recuperacion_verificada_en',
            ]);

            return redirect()->route('seguridad.recuperacion.solicitar')
                ->withErrors(['email' => 'El proceso de recuperación venció. Solicita un nuevo código.']);
        }

        $datos = $actualizarContrasenaRequest->validated();

        if (! $this->recuperacionService->actualizarContrasena($email, $datos['password'])) {
            $sesion->forget([
                'recuperacion_email',
                'recuperacion_verificada',
                'recuperacion_verificada_en',
            ]);

            return redirect()->route('seguridad.recuperacion.solicitar');
        }

        $sesion->forget([
            'recuperacion_verificada',
            'recuperacion_verificada_en',
        ]);

        return redirect()->route('login')
            ->with('estado', 'Tu contraseña fue actualizada correctamente. Ya puedes iniciar sesión.');
    }
}
