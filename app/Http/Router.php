<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\Controller\CharacterController;
use App\Http\Controller\HealthController;

final class Router
{
    public static function dispatch(string $method, string $uri): array
    {
        if ($method === 'GET' && $uri === '/health') {
            return (new HealthController())->handle();
        }

        if ($method === 'GET' && $uri === '/characters') {
            return (new CharacterController())->index();
        }

        return [
            'status' => 404,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['error' => 'Not Found']),
        ];
    }
}