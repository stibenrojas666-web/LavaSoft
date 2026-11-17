<?php

namespace App\Repositories\Contracts;

/**
 * Contrato de acceso a datos para AgendaRepository.
 * @method mixed getAll(...$arguments)
 * @method mixed buscar(...$arguments)
 * @method mixed buscarParaCalendario(...$arguments)
 * @method mixed totalServicios(...$arguments)
 * @method mixed keep(...$arguments)
 * @method mixed findById(...$arguments)
 * @method mixed vehiculoOcupado(...$arguments)
 * @method mixed vehiculoPerteneceACliente(...$arguments)
 * @method mixed vehiculoActivo(...$arguments)
 * @method mixed servicioActivo(...$arguments)
 * @method mixed empleadoActivo(...$arguments)
 * @method mixed empleadoTieneTurno(...$arguments)
 * @method mixed empleadoOcupado(...$arguments)
 * @method mixed update(...$arguments)
 * @method mixed quitarLineas(...$arguments)
 * @method mixed delete(...$arguments)
 * @method mixed tieneDependencias(...$arguments)
 * @method mixed tienePago(...$arguments)
 * @method mixed citasActivasDelDia(...$arguments)
 * @method mixed duracionesServicios(...$arguments)
 */
interface AgendaRepositoryInterface
{
}
