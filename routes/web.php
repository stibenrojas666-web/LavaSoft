<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoVehiculoController;

Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

Route::resource('empleados',EmpleadoController::class);
Route::resource('clientes',ClientesController::class);
Route::resource('servicios', ServicioController::class);
Route::resource('tipoVehiculo', TipoVehiculoController::class);