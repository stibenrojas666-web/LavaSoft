<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard.index');

// ClienteController is not available yet, so this route is kept as a simple placeholder.
Route::get('/clientes', function () {
    return 'Clientes';
})->name('clientes.index');