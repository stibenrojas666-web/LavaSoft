<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaServiciosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\TipoVehiculoController;
use App\Http\Controllers\PrecioServicioController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\PagoController;
use App\Http\Middleware\NoCacheHeaders;



Route::middleware(['auth', NoCacheHeaders::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard.index')->middleware('modulo:dashboard');
            
    Route::resource('empleados', EmpleadoController::class)->middleware('modulo:empleados');
    Route::resource('clientes', ClientesController::class)->middleware('modulo:clientes');
    Route::patch('vehiculos/{id}/estado', [VehiculoController::class, 'cambiarEstado'])
        ->whereNumber('id')->name('vehiculos.estado')->middleware('modulo:vehiculos');
    Route::resource('vehiculos', VehiculoController::class)->middleware('modulo:vehiculos');
    Route::patch('empleados/{id}/estado', [EmpleadoController::class, 'cambiarEstado'])
        ->whereNumber('id')->name('empleados.estado')->middleware('modulo:empleados');
    Route::patch('servicios/{id}/estado', [ServicioController::class, 'cambiarEstado'])
        ->whereNumber('id')->name('servicios.estado')->middleware('modulo:servicios');
    Route::resource('servicios', ServicioController::class)->middleware('modulo:servicios');
    Route::resource('tipoVehiculo', TipoVehiculoController::class)->middleware('modulo:tipoVehiculo');
    Route::get('agenda/empleados-disponibles', [AgendaController::class, 'empleadosDisponibles'])
        ->name('agenda.empleadosDisponibles')->middleware('modulo:agenda');
    Route::resource('agenda', AgendaController::class)->middleware('modulo:agenda');
    Route::get('agenda/{agendaId}/factura', [PagoController::class, 'factura'])
        ->whereNumber('agendaId')->name('agenda.factura')->middleware('modulo:agenda');
    Route::post('agenda/{agendaId}/pago', [PagoController::class, 'registrarPago'])
        ->whereNumber('agendaId')->name('agenda.pago.registrar')->middleware('modulo:agenda');
    Route::post('agenda/{agendaId}/factura/correo', [PagoController::class, 'enviarCorreo'])
        ->whereNumber('agendaId')->name('agenda.factura.correo')->middleware('modulo:agenda');
    Route::get('pagos/historial', [PagoController::class, 'historial'])
        ->name('pagos.historial')->middleware('modulo:pagos');
    Route::resource('precioServicio', PrecioServicioController::class)->middleware('modulo:precioServicio');
    Route::resource('turnos', TurnoController::class)->middleware('modulo:turnos');
    Route::resource('agendaServicio', AgendaServiciosController::class)->middleware('modulo:agendaServicio');
});
