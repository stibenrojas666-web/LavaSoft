<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaIndexRequest;
use App\Http\Requests\ClienteStoreRequest;
use App\Http\Requests\ClienteUpdateRequest;
use App\Services\ClienteService;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    private ClienteService $clientesService;

    public function __construct(ClienteService $clientesService)
    {
        $this->clientesService = $clientesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BusquedaIndexRequest $busquedaIndexRequest)
    {
        return view('clientes.index', $this->clientesService->prepararListado(
            $busquedaIndexRequest->validated()
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteStoreRequest $clienteStoreRequest)
    {
        $cliente = $this->clientesService->guardar($clienteStoreRequest->validated());

        return redirect()->route('vehiculos.create', ['clienteId' => $cliente->id])
            ->with('success', 'Cliente creado correctamente. Ahora puedes agregar su vehículo.');

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cliente = $this->clientesService->buscarConHistorial($id);

        return view('clientes.historial', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $cliente = $this->clientesService->buscarPorId($id);

        return view('clientes.editar', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, ClienteUpdateRequest $clienteUpdateRequest)
    {
        $this->clientesService->actualizar($id, $clienteUpdateRequest->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $modo = $request->input('modo', 'archivar');

        if ($modo === 'definitivo') {
            abort_unless($request->user()?->can(config('seguridad.permiso_admin')), 403);
            $this->clientesService->eliminarTodo($id);

            return redirect()->route('clientes.index')->with('success', 'Se eliminó el cliente junto con sus vehículos, citas, servicios e historial de pagos.');
        }

        abort_unless($request->user()?->can('eliminar clientes'), 403);
        abort_unless($modo === 'archivar', 422);
        $this->clientesService->archivar($id);

        return redirect()->route('clientes.index', ['archivados' => 1])->with('success', 'Cliente archivado. Sus vehículos, citas y pagos se conservaron en el historial.');
    }

    public function reactivar(Request $request, int $id)
    {
        abort_unless($request->user()?->can('eliminar clientes'), 403);
        $this->clientesService->reactivar($id);

        return redirect()->to(route('clientes.index', ['archivados' => 1, ...$request->query()]).'#clientes-listado')
            ->with('success', 'Cliente reactivado y disponible nuevamente para registrar citas.');
    }
}
