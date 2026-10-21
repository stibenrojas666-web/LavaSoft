<?php

namespace App\Http\Controllers;

use App\Http\Requests\pagoStoreRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Services\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function __construct(private PagoService $pagoService)
    {
    }

    public function historial(BusquedaIndexRequest $request): View
    {
        $datos = $request->validated();
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['metodo', 'desde', 'hasta']));
        return view('pagos.historial', [
            'pagos' => $this->pagoService->buscarHistorialPagos($buscar, $filtros),
            'buscar' => $buscar,
            'filtros' => $filtros,
        ]);
    }

    public function factura(int $agendaId): View
    {
        return view('pagos.factura', $this->pagoService->obtenerFactura($agendaId));
    }

    public function registrarPago(int $agendaId, pagoStoreRequest $request): RedirectResponse
    {
        $this->pagoService->registrarPago($agendaId, $request->validated());

        return redirect()->route('agenda.factura', $agendaId)
            ->with('success', 'Pago registrado y cita marcada como terminada.');
    }

    public function enviarCorreo(int $agendaId): RedirectResponse
    {
        $this->pagoService->enviarFacturaPorCorreo($agendaId);

        return redirect()->route('agenda.factura', $agendaId)
            ->with('success', 'Factura enviada al correo registrado del cliente.');
    }
}
