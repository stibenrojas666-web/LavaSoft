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



Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard.index')->middleware('modulo:dashboard');
    Route::resource('empleados', EmpleadoController::class)->middleware('modulo:empleados');
    Route::resource('clientes', ClientesController::class)->middleware('modulo:clientes');
    Route::resource('vehiculos', VehiculoController::class)->middleware('modulo:vehiculos');
    Route::resource('servicios', ServicioController::class)->middleware('modulo:servicios');
    Route::resource('tipoVehiculo', TipoVehiculoController::class)->middleware('modulo:tipoVehiculo');
    Route::get('agenda/empleados-disponibles', [AgendaController::class, 'empleadosDisponibles'])
        ->name('agenda.empleadosDisponibles')->middleware('modulo:agenda');
    Route::resource('agenda', AgendaController::class)->middleware('modulo:agenda');
    Route::resource('precioServicio', PrecioServicioController::class)->middleware('modulo:precioServicio');
    Route::resource('turnos', TurnoController::class)->middleware('modulo:turnos');
    Route::resource('agendaServicio', AgendaServiciosController::class)->middleware('modulo:agendaServicio');
    Route::resource('pagos', PagoController::class)->names('pagos')->middleware('modulo:pagos');

});
