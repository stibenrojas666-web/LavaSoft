<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaStoreRequest;
use App\Http\Requests\AgendaUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\EmpleadosDisponiblesRequest;
use App\Services\AgendaService;
use App\Services\EmpleadoService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(
        private AgendaService $agendaService,
        private EmpleadoService $empleadoService
    ) {
    }

    public function index(BusquedaIndexRequest $request)
    {
        return view('Agenda.index', $this->agendaService->prepararIndice($request->validated()));
    }

    public function create()
    {
        return view('Agenda.crear', $this->agendaService->prepararFormulario(
            null,
            old('vehiculos')
        ));
    }

    public function store(AgendaStoreRequest $request)
    {
        $datos = $request->validated();
        $this->agendaService->keep($datos);
        $retorno = $this->agendaService->parametrosRetornoTrasGuardar(
            array_merge($request->query(), $request->all()),
            $datos
        );

        return redirect()->route('agenda.index', $retorno)->with('success', 'Cita agendada correctamente.');
    }

    public function show()
    {
        // La ruta se conserva por compatibilidad con los recursos registrados.
    }

    public function edit(int $id)
    {
        $datos = $this->agendaService->prepararFormulario($id, old('vehiculos'));
        if (! $this->agendaService->puedeEditar($datos['agenda'])) {
            return redirect()->route('agenda.index')->with('error', 'Una cita terminada ya no se puede editar.');
        }

        return view('Agenda.editar', $datos);
    }

    public function empleadosDisponibles(EmpleadosDisponiblesRequest $request)
    {
        return response()->json($this->empleadoService->disponiblesParaAgenda($request->validated()));
    }

    public function update(int $id, AgendaUpdateRequest $request)
    {
        $datos = $request->validated();
        $this->agendaService->update($id, $datos);
        $retorno = $this->agendaService->parametrosRetornoTrasGuardar(
            array_merge($request->query(), $request->all()),
            $datos
        );

        return redirect()->route('agenda.index', $retorno)->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Request $request, int $id)
    {
        $retorno = $this->agendaService->parametrosRetorno(
            array_merge($request->query(), $request->all())
        );

        if (! $this->agendaService->delete($id)) {
            return redirect()->route('agenda.index', $retorno)
                ->with('error', 'No se puede eliminar la cita porque ya tiene un pago registrado.');
        }

        return redirect()->route('agenda.index', $retorno)->with('success', 'Cita eliminada correctamente.');
    }
}
