<?php

namespace App\Http\Controllers;

use App\Models\vehiculo;
use Illuminate\Http\Request;
use App\Services\VehiculoService;
use App\Services\clienteService;
/**use App\Services\clienteService; */

/**use App\Services\*/
class VehiculoController extends Controller
{
    
    private VehiculoService $VehiculoService;
    private clienteService $clienteService;
    /* private clienteService $clienteService; */

    public function __construct(VehiculoService $VehiculoService, clienteService $clienteService/***/){
        $this->VehiculoService = $VehiculoService;
        $this->clienteService =$clienteService;
        /**$this->tipoVehiculo =$clienteService; */
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculo= $this->VehiculoService->getAll();
        return view('Vehiculo.index',compact('vehiculo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cliente = $this->clienteService->listarTodo();
        /** $tipoVehiculo = $this->tipoVehiculo->getAll(); */
        return view ('vehiculos.crear',compact('cliente'/** ,tipoVehiculo*/));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vehiculo $vehiculo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(vehiculo $vehiculo)
    {
        //
    }
}
