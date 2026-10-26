<?php

namespace App\Services;

use App\Models\ConfiguracionLavadero;
use App\Repositories\ConfiguracionLavaderoRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ConfiguracionLavaderoService
{
    public function __construct(private ConfiguracionLavaderoRepository $repository)
    {
    }

    public function obtener(): ConfiguracionLavadero
    {
        if (!Schema::hasTable('configuracion_lavadero')) {
            return new ConfiguracionLavadero([
                'nombre' => config('lavadero.nombre', 'LavaSoft'),
                'nit' => config('lavadero.nit'),
                'direccion' => config('lavadero.direccion'),
                'telefono' => config('lavadero.telefono'),
                'mostrar_logo' => true,
            ]);
        }

        $atributos = Cache::remember(
            'configuracion_lavadero_v2',
            now()->addMinutes(5),
            fn () => $this->repository->obtener()->getAttributes()
        );

        return (new ConfiguracionLavadero())->forceFill($atributos);
    }

    public function actualizar(array $datos, ?UploadedFile $logo = null): ConfiguracionLavadero
    {
        $actual = $this->repository->obtener();
        $rutaAnterior = $actual->logo_path;
        $mostrarLogo = filter_var($datos['mostrar_logo'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $datos['mostrar_logo'] = $mostrarLogo;
        $rutaNueva = $logo?->store('lavadero', 'public');

        if ($logo !== null && $rutaNueva === false) {
            throw ValidationException::withMessages([
                'logo' => 'No se pudo guardar el logo. Revisa que el archivo pese menos de 2 MB e inténtalo de nuevo.',
            ]);
        }

        if ($rutaNueva !== null) {
            $datos['logo_path'] = $rutaNueva;
            $datos['mostrar_logo'] = true;
        } elseif (!$mostrarLogo) {
            $datos['logo_path'] = null;
        }

        try {
            $configuracion = DB::transaction(fn () => $this->repository->guardar($datos));
        } catch (Throwable $error) {
            if ($rutaNueva !== null) {
                Storage::disk('public')->delete($rutaNueva);
            }

            throw $error;
        }

        if (($rutaNueva !== null || !$mostrarLogo) && $rutaAnterior !== null) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        Cache::forget('configuracion_lavadero_v2');

        return $configuracion;
    }
}
