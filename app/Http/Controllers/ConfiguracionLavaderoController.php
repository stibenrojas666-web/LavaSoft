<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracionLavaderoRequest;
use App\Services\ConfiguracionLavaderoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConfiguracionLavaderoController extends Controller
{
    public function __construct(private ConfiguracionLavaderoService $service)
    {
    }

    public function edit(): View
    {
        return view('configuracion.edit', ['configuracion' => $this->service->obtener()]);
    }

    public function logo(): BinaryFileResponse
    {
        $ruta = $this->service->rutaLogoVisible();
        abort_unless($ruta, 404);

        return response()->file($ruta, [
            'Cache-Control' => 'public, max-age=0, must-revalidate',
        ]);
    }

    public function update(ConfiguracionLavaderoRequest $configuracionLavaderoRequest): RedirectResponse
    {
        $this->service->actualizar(
            $configuracionLavaderoRequest->validated(),
            $configuracionLavaderoRequest->file('logo')
        );

        return redirect()->route('configuracion.edit')->with('success', 'Configuración del lavadero actualizada.');
    }
}
