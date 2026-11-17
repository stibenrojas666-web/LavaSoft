<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;
use App\Http\Requests\ClienteStoreRequest;
use App\Http\Requests\ClienteUpdateRequest;
use App\Http\Requests\BusquedaIndexRequest;


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
        $this->clientesService->guardar($clienteStoreRequest->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');

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
        return view ('clientes.editar', compact('cliente'));
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
    public function destroy(int $id)
    {
        if (!$this->clientesService->eliminar($id)) {
            return redirect()->route('clientes.index')->with('error', 'No se puede eliminar el cliente porque tiene vehículos o citas relacionados.');
        }
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
