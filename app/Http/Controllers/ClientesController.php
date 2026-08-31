<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;
use App\Services\clienteService;
use App\Http\Requests\ClienteStoreRequest;

class ClientesController extends Controller
{
    private clienteService $clientesService;

    public function __construct(clienteService $clientesService)
    {
        $this->clientesService = $clientesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = $this->clientesService->listarTodo();
        return view('clientes.index', compact('clientes'));
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
    public function store(ClienteStoreRequest $request)
    {
        $datos = $request->validated();
        $this->clientesService->guardar($datos);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');

    }

    

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->clientesService->eliminar($id);
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
