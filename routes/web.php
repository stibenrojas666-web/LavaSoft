<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientesController;


Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

Route::resource('clientes', ClientesController::class);
