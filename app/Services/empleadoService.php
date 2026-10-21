<?php 

namespace App\Services;

use App\Repositories\empleadoRepository;
use Carbon\Carbon;

class empleadoService
{
    private empleadoRepository $empleadoRepository;
    
    public function __construct(empleadoRepository $empleadoRepository)
{
    $this->empleadoRepository = $empleadoRepository;    
}

public function listarTodo(){
    return $this->empleadoRepository->listarTodo();
}

    public function buscar(string $texto, array $filtros = [])
{
        return $this->empleadoRepository->buscar($texto, $filtros);
}

    public function disponiblesEnHorario(string $fecha, string $hora, ?int $agendaId = null)
    {
        $dias = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'];
        $dia = $dias[Carbon::parse($fecha)->dayOfWeekIso];

        return $this->empleadoRepository->disponiblesEnHorario($fecha, $hora, $dia, $agendaId);
    }

public function guardar(array $datos){
    return $this->empleadoRepository->guardar($datos);
}

    public function eliminar(int $id): bool
    {
        if ($this->empleadoRepository->tieneDependencias($id)) {
            return false;
        }

        return $this->empleadoRepository->eliminar($id) > 0;
}

public function buscarPorId(int $id){
    return $this->empleadoRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->empleadoRepository->actualizar($id, $datos);
}

public function cambiarEstado(int $id): bool
{
    $empleado = $this->empleadoRepository->buscarPorId($id);
    $nuevoEstado = !(bool) $empleado->estado;

    $this->empleadoRepository->actualizar($id, ['estado' => $nuevoEstado]);

    return $nuevoEstado;
}

}
