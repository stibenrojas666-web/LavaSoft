<?php

return [
    // Estos valores se configuran por instalación desde el archivo .env.
    'apertura_regular' => env('LAVADERO_APERTURA_REGULAR', '07:00'),
    'cierre_regular' => env('LAVADERO_CIERRE_REGULAR', '18:00'),
    'apertura_domingo_festivo' => env('LAVADERO_APERTURA_DOMINGO_FESTIVO', '08:00'),
    'cierre_domingo_festivo' => env('LAVADERO_CIERRE_DOMINGO_FESTIVO', '13:00'),
    // Fechas en formato YYYY-MM-DD, separadas por coma.
    'festivos' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('LAVADERO_FESTIVOS', ''))
    ))),
];
