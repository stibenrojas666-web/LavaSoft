<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoStoreRequest;
use App\Http\Requests\PagoUpdateRequest;
use App\Services\AgendaService;
use App\Services\PagoService;

class PagoController extends Controller
{
    private PagoService $pagoService;
    private AgendaService $agendaService;

    public function __construct(PagoService $pagoService, AgendaService $agendaService)
    {
        $this->pagoService = $pagoService;
        $this->agendaService = $agendaService;
    }

    public function index()
    {
        $pago = $this->pagoService->getAll();

        return view('pagos.index', compact('pago'));
    }

    public function create()
    {
        $agenda = $this->agendaService->getAll();

        return view('pagos.crear', compact('agenda'));
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
        $agenda = $this->agendaService->getAll();

        return view('pagos.editar', compact('pago', 'agenda'));
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