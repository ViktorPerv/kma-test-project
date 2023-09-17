<?php

declare(strict_types=1);

namespace App\Routing;

class Route
{
    public static function get(string $uri, array $handler): array
    {
        return ['GET', $uri, $handler];
    }
}
