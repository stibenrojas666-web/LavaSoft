<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

Route::resource('Categoria',EmpleadosController::class);