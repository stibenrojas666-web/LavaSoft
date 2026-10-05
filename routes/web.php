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
use App\Http\Controllers\PagoController;


Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

Route::resource('empleados',EmpleadoController::class);
Route::resource('clientes',ClientesController::class);
Route::resource('servicios',ServicioController::class);
Route::resource('vehiculos',VehiculoController::class);
Route::resource('servicios', ServicioController::class);
Route::resource('tipoVehiculo', TipoVehiculoController::class);
Route::resource('agenda',AgendaController::class);
Route::resource('precioServicio',PrecioServicioController::class);
Route::resource('agendaServicio',AgendaServiciosController::class);
Route::resource('pagos', PagoController::class);