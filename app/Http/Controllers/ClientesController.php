<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Services\clienteService;
use App\Http\Requests\ClienteStoreRequest;
use App\Http\Requests\ClienteUpdateRequest;


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
    public function edit(int $id)
    {
        $cliente = clientes::findOrFail($id);
        return view ('clientes.editar', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, clienteUpdateRequest $request){
        $cliente = clientes::FindOrFail($id);
         $cliente->update($request->validated());
        
        return redirect()->route('clientes.index')->with('success','cliente actualizado');
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
