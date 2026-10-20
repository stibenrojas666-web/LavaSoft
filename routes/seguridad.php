<?php

use App\Http\Controllers\Seguridad\AuthController;
use App\Http\Controllers\Seguridad\CuentaController;
use App\Http\Controllers\Seguridad\PermisoController;
use App\Http\Controllers\Seguridad\RolController;
use App\Http\Controllers\Seguridad\UsuarioRolController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::prefix('seguridad')
            ->name('seguridad.')
            ->middleware('can:' . config('seguridad.permiso_admin'))
            ->group(function () {
                Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
                Route::get('/roles/crear', [RolController::class, 'create'])->name('roles.create');
                Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
                Route::get('/roles/{rol}/editar', [RolController::class, 'edit'])->name('roles.edit');
                Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
                Route::patch('/roles/{rol}/estado', [RolController::class, 'estado'])->name('roles.estado');
                Route::delete('/roles/{rol}', [RolController::class, 'destroy'])->name('roles.destroy');

                Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
                Route::post('/permisos', [PermisoController::class, 'store'])->name('permisos.store');
                Route::post('/permisos/modulo', [PermisoController::class, 'storeModulo'])->name('permisos.modulo');
                Route::put('/permisos/{permiso}', [PermisoController::class, 'update'])->name('permisos.update');
                Route::delete('/permisos/{permiso}', [PermisoController::class, 'destroy'])->name('permisos.destroy');

                Route::get('/cuentas', [CuentaController::class, 'index'])->name('cuentas.index');
                Route::get('/cuentas/crear', [CuentaController::class, 'create'])->name('cuentas.create');
                Route::post('/cuentas', [CuentaController::class, 'store'])->name('cuentas.store');
                Route::get('/cuentas/{cuenta}/editar', [CuentaController::class, 'edit'])->name('cuentas.edit');
                Route::put('/cuentas/{cuenta}', [CuentaController::class, 'update'])->name('cuentas.update');
                Route::delete('/cuentas/{cuenta}', [CuentaController::class, 'destroy'])->name('cuentas.destroy');

                Route::get('/usuarios', [UsuarioRolController::class, 'index'])->name('usuarios.index');
                Route::put('/usuarios', [UsuarioRolController::class, 'update'])->name('usuarios.update');
            });
    });
});
