<?php

namespace App\Support;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TextoBusqueda
{
    /**
     * Cada palabra escrita debe aparecer al menos en uno de los campos buscables.
     * Las palabras pueden coincidir en campos distintos, como "lunes mañana".
     */
    public static function aplicar(Builder $consulta, string $texto, Closure $camposCoincidentes): Builder
    {
        $terminos = preg_split('/\s+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $terminos = array_values(array_filter(array_map(
            fn (string $termino) => trim($termino, ".,;:!?()[]{}\"'"),
            $terminos
        ), fn (string $termino) => $termino !== ''));

        foreach ($terminos as $termino) {
            $comodin = '%' . $termino . '%';
            $consulta->where(fn (Builder $grupo) => $camposCoincidentes($grupo, $comodin));
        }

        return $consulta;
    }
}
