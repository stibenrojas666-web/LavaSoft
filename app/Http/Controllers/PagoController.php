<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoStoreRequest;
use App\Http\Requests\BusquedaIndexRequest;
use App\Services\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function __construct(private PagoService $pagoService)
    {
    }

    public function historial(BusquedaIndexRequest $busquedaIndexRequest): View
    {
        return view('pagos.historial', $this->pagoService->prepararHistorial(
            $busquedaIndexRequest->validated()
        ));
    }

    public function factura(int $agendaId): View
    {
        return view('pagos.factura', $this->pagoService->obtenerFactura($agendaId));
    }

    public function registrarPago(int $agendaId, PagoStoreRequest $pagoStoreRequest): RedirectResponse
    {
        $this->pagoService->registrarPago($agendaId, $pagoStoreRequest->validated());

        return redirect()->route('agenda.factura', $agendaId)
            ->with('success', 'Pago registrado y cita marcada como terminada.');
    }

    public function enviarCorreo(int $agendaId): RedirectResponse
    {
        $this->pagoService->enviarFacturaPorCorreo($agendaId);

        return redirect()->route('agenda.factura', $agendaId)
            ->with('success', 'Factura enviada al correo registrado del cliente.');
    }

        public function facturaPublica(int $agendaId): View
    {
        return view('pagos.factura-publica', $this->pagoService->obtenerFacturaPublica($agendaId));
    }
}
