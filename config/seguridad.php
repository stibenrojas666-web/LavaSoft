<?php

return [
    'guard' => 'web',

    'rol_admin' => 'administrador',

    'permiso_admin' => 'gestionar seguridad',

    'roles_protegidos' => ['administrador'],

    'roles_no_desactivables' => ['administrador'],

    'acciones' => ['ver', 'crear', 'editar', 'eliminar'],

    'metodos_a_acciones' => [
        'index' => 'ver',
        'show' => 'ver',
        'create' => 'crear',
        'store' => 'crear',
        'edit' => 'editar',
        'update' => 'editar',
        'destroy' => 'eliminar',
        'empleadosDisponibles' => 'ver',
    ],

    'auditar_prefijos' => ['App\\'],

    'redirect_despues_login' => '/dashboard',

    'modulos' => [
        'dashboard',
        'empleados',
        'clientes',
        'vehiculos',
        'servicios',
        'tipoVehiculo',
        'agenda',
        'precioServicio',
        'turnos',
        'agendaServicio',
        'pagos',
    ],

    'admin' => [
        'name' => env('SEGURIDAD_ADMIN_NAME', 'Administrador'),
        'email' => env('SEGURIDAD_ADMIN_EMAIL', 'admin@admin.com'),
        'password' => env('SEGURIDAD_ADMIN_PASSWORD', '12345678'),
    ],
];
