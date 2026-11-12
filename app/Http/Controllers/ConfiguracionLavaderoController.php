<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracionLavaderoRequest;
use App\Services\ConfiguracionLavaderoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConfiguracionLavaderoController extends Controller
{
    public function __construct(private ConfiguracionLavaderoService $service)
    {
    }

    public function edit(): View
    {
        return view('configuracion.edit', ['configuracion' => $this->service->obtener()]);
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
