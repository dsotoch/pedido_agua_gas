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
                'path' => '/images/icons/favicon1-72x72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/images/icons/favicon1-96x96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/images/icons/favicon1-128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/images/icons/favicon1-144x144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/images/icons/favicon1-152x152.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/images/icons/favicon1-192x192.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/images/icons/favicon1-384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/images/icons/favicon1-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/splash/splash-640x1136.png',
            '750x1334' => '/splash/splash-750x1334.png',
            '828x1792' => '/splash/splash-828x1792.png',
            '1125x2436' => '/splash/splash-1125x2436.png',
            '1242x2208' => '/splash/splash-1242x2208.png',
            '1242x2688' => '/splash/splash-1242x2688.png',
            '1536x2048' => '/splash/splash-1536x2048.png',
            '1668x2224' => '/splash/splash-1668x2224.png',
            '1668x2388' => '/splash/splash-1668x2388.png',
            '2048x2732' => '/splash/splash-2048x2732.png',

        ],
        'shortcuts' => [],
        'custom' => []
    ]
];
