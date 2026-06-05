<?php

namespace App\Support;

class FerryRoutes
{
    const ROUTES = [
        'port-dickson-dumai' => [
            'key'         => 'port-dickson-dumai',
            'origin'      => 'Port Dickson',
            'destination' => 'Dumai',
            'price'       => 265,
            'label'       => 'Ferry Port Dickson → Dumai',
        ],
        'port-dickson-tanjung-balai' => [
            'key'         => 'port-dickson-tanjung-balai',
            'origin'      => 'Port Dickson',
            'destination' => 'Tanjung Balai',
            'price'       => 265,
            'label'       => 'Ferry Port Dickson → Tanjung Balai',
        ],
        'stulang-laut-batam' => [
            'key'         => 'stulang-laut-batam',
            'origin'      => 'Stulang Laut',
            'destination' => 'Batam Center',
            'price'       => 225,
            'label'       => 'Ferry Stulang Laut → Batam Center',
        ],
    ];

    public static function all(): array
    {
        return self::ROUTES;
    }

    public static function find(string $key): ?array
    {
        return self::ROUTES[$key] ?? null;
    }

    public static function keys(): array
    {
        return array_keys(self::ROUTES);
    }
}
