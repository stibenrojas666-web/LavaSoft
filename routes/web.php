<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;

Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

Route::resource('empleados',EmpleadoController::class);