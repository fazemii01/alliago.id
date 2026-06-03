<?php

namespace App\Support;

class FerryRoutes
{
    const ROUTES = [
        'pidi-dumai' => [
            'key'         => 'pidi-dumai',
            'origin'      => 'Pidi',
            'destination' => 'Dumai',
            'price'       => 1125000,
            'label'       => 'Ferry Pidi → Dumai',
        ],
        'pidi-tanjung-balai' => [
            'key'         => 'pidi-tanjung-balai',
            'origin'      => 'Pidi',
            'destination' => 'Tanjung Balai',
            'price'       => 1125000,
            'label'       => 'Ferry Pidi → Tanjung Balai',
        ],
        'stulang-laut-batam' => [
            'key'         => 'stulang-laut-batam',
            'origin'      => 'Stulang Laut',
            'destination' => 'Batam',
            'price'       => 900000,
            'label'       => 'Ferry Stulang Laut → Batam',
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
