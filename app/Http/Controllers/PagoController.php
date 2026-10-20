<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoStoreRequest;
use App\Http\Requests\PagoUpdateRequest;
use App\Http\Requests\PagoCreateRequest;
use App\Services\PagoService;

class PagoController extends Controller
{
    private PagoService $pagoService;
    public function __construct(PagoService $pagoService)
    {
        $this->pagoService = $pagoService;
    }

    public function index()
    {
        $pago = $this->pagoService->getAll();

        return view('pagos.index', compact('pago'));
    }

    public function create(PagoCreateRequest $request)
    {
        $datos = $request->validated();
        $formulario = $this->pagoService->datosParaCrear(
            isset($datos['agendaId']) ? (int) $datos['agendaId'] : null
        );

        return view('pagos.crear', $formulario);
    }

    public function store(PagoStoreRequest $request)
    {
        $this->pagoService->keep($request->validated());

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show()
    {
        //
    }

    public function edit(int $id)
    {
        $pago = $this->pagoService->findById($id);
        $total = $this->pagoService->calcularTotal((int) $pago->agendaId);

        return view('pagos.editar', compact('pago', 'total'));
    }

    public function update(int $id, PagoUpdateRequest $request)
    {
        $this->pagoService->update($id, $request->validated());

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $this->pagoService->delete($id);

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
}
