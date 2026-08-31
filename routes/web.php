<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EmpleadosController;

Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

Route::resource('Categoria',EmpleadosController::class);

Route::resource('clientes', ClientesController::class);
