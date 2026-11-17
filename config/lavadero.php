<?php

return [
    'nombre' => env('LAVADERO_NOMBRE', 'LavaSoft'),
    'nit' => env('LAVADERO_NIT'),
    'direccion' => env('LAVADERO_DIRECCION'),
    'telefono' => env('LAVADERO_TELEFONO'),
    'apertura_regular' => '07:00',
    'cierre_regular' => '18:00',
    'apertura_domingo_festivo' => '08:00',
    'cierre_domingo_festivo' => '13:00',
    'festivos' => [
        '2026-11-16', '2026-12-08', '2026-12-25',
    ],
];
