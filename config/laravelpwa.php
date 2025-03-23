<?php

return [
    'name' => 'Entrega.pe',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => 'Entrega.pe',
        'start_url' => '/',
        'background_color' => '#293242',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'portrait',
        'status_bar' => 'black',
        'icons' => [
            '72x72' => [
                'path' => '/imagenes/images/icons/favicon-72x72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/imagenes/images/icons/favicon-96x96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/imagenes/images/icons/favicon-128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/imagenes/images/icons/favicon-144x144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/imagenes/images/icons/favicon-152x152.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/imagenes/images/icons/favicon-192x192.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/imagenes/images/icons/favicon-384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/imagenes/images/icons/favicon-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/imagenes/splash/splash-640x1136.png',
            '750x1334' => '/imagenes/splash/splash-750x1334.png',
            '828x1792' => '/imagenes/splash/splash-828x1792.png',
            '1125x2436' => '/imagenes/splash/splash-1125x2436.png',
            '1242x2208' => '/imagenes/splash/splash-1242x2208.png',
            '1242x2688' => '/imagenes/splash/splash-1242x2688.png',
            '1536x2048' => '/imagenes/splash/splash-1536x2048.png',
            '1668x2224' => '/imagenes/splash/splash-1668x2224.png',
            '1668x2388' => '/imagenes/splash/splash-1668x2388.png',
            '2048x2732' => '/imagenes/splash/splash-2048x2732.png',

        ],
        'shortcuts' => [],
        'custom' => []
    ]
];
