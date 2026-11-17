<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaStoreRequest;
use App\Http\Requests\AgendaUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\CambiarEstadoAgendaRequest;
use App\Http\Requests\EmpleadosDisponiblesRequest;
use App\Services\AgendaService;
use App\Services\EmpleadoService;
use App\Services\NotificacionCitaService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(
        private AgendaService $agendaService,
        private EmpleadoService $empleadoService,
        private NotificacionCitaService $notificacionCitaService
    ) {}

    public function index(BusquedaIndexRequest $request)
    {
        return view('Agenda.index', $this->agendaService->prepararIndice($request->validated()));
    }

    public function create(Request $request)
    {
        $datos = $this->agendaService->prepararFormulario(null, old('vehiculos'));
        $clienteId = $request->query('clienteId');
        $vehiculoId = (int) $request->query('vehiculoId', 0);
        $vehiculoSeleccionado = $datos['vehiculo']->firstWhere('id', $vehiculoId);

        $datos['clienteSeleccionadoId'] = $clienteId;

        if ($vehiculoSeleccionado && (int) $vehiculoSeleccionado->clienteID === (int) $clienteId) {
            if (old('vehiculos') === null) {
                $datos['bloquesIniciales'] = $this->agendaService->bloquesIniciales(
                    null,
                    [[
                        'vehiculoId' => $vehiculoSeleccionado->id,
                        'servicios' => [['servicioId' => '', 'precioAcordado' => '']],
                    ]],
                    $datos['serviciosPrecios']
                );
            }

            $datos['urlVolverVehiculo'] = route('vehiculos.edit', $vehiculoSeleccionado->id).'?continuarAgenda=1';
        }

        return view('Agenda.crear', $datos);
    }

    public function store(AgendaStoreRequest $request)
    {
        $datos = $request->validated();
        $agenda = $this->agendaService->keep($datos);
        $correoEnviado = $agenda->estado === 'cancelada'
            ? true
            : $this->notificacionCitaService->enviarSolicitud($agenda);
        $retorno = $this->agendaService->parametrosRetornoTrasGuardar(
            array_merge($request->query(), $request->all()),
            $datos
        );

        $respuesta = redirect()->route('agenda.index', $retorno)->with('success', 'Cita agendada correctamente.');
        if ($agenda->estado === 'cancelada') {
            return $respuesta;
        }

        return $correoEnviado
            ? $respuesta->with('info', 'Enviamos al cliente un aviso con los detalles de su cita.')
            : $respuesta->with('warning', 'La cita se guardó, pero no se pudo enviar el aviso. Revisa que el cliente tenga un correo registrado.');
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

    public function cambiarEstado(CambiarEstadoAgendaRequest $request, int $id)
    {
        $estado = (string) $request->validated('estado');
        $this->agendaService->cambiarEstadoRapido($id, $estado);
        $agenda = $this->agendaService->findById($id);
        $correoEnviado = $this->notificacionCitaService->enviarEstado($agenda, $estado);

        return redirect()->to(route('agenda.index', $request->query()).'#agenda-'.$id)
            ->with($correoEnviado ? 'success' : 'warning', $correoEnviado ? 'Estado actualizado y notificación enviada al cliente.' : 'Estado actualizado, pero no se pudo notificar al cliente.');
    }

    public function empleadosDisponibles(EmpleadosDisponiblesRequest $request)
    {
        return response()->json($this->empleadoService->disponiblesParaAgenda($request->validated()));
    }

    public function update(int $id, AgendaUpdateRequest $request)
    {
        $datos = $request->validated();
        $estadoAnterior = $this->agendaService->findById($id)->estado;
        $this->agendaService->update($id, $datos);
        $correoEstadoEnviado = true;
        if (($datos['estado'] ?? $estadoAnterior) !== $estadoAnterior) {
            $correoEstadoEnviado = $this->notificacionCitaService->enviarEstado(
                $this->agendaService->findById($id),
                $datos['estado']
            );
        }
        $retorno = $this->agendaService->parametrosRetornoTrasGuardar(
            array_merge($request->query(), $request->all()),
            $datos
        );

        $respuesta = redirect()->route('agenda.index', $retorno);

        return $correoEstadoEnviado
            ? $respuesta->with('success', 'Cita actualizada correctamente.')
            : $respuesta->with('warning', 'La cita se actualizó, pero no se pudo notificar al cliente por correo.');
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
