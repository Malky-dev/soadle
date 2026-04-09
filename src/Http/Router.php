<?php

declare(strict_types=1);

namespace App\Http;

use App\Application\CompareCharacters;
use App\Application\PlayGame;
use App\Http\Controller\CharacterController;
use App\Http\Controller\GameController;
use App\Http\Controller\HealthController;
use App\Http\Controller\HomeController;
use App\Infrastructure\CharacterSqlRepository;
use App\Infrastructure\Database\Connection;

final class Router
{
    public static function dispatch(string $method, string $uri): array
    {
        $uri = rtrim($uri, '/') ?: '/';

        $pdo = Connection::get();
        $repo = new CharacterSqlRepository($pdo);
        $playGame = new PlayGame($repo, new CompareCharacters());

        if ($method === 'GET' && $uri === '/health') {
            return (new HealthController())->handle();
        }

        if ($method === 'GET' && $uri === '/characters') {
            return (new CharacterController())->index();
        }

        if ($method === 'GET' && $uri === '/') {
            return (new HomeController())->index();
        }

        if ($method === 'GET' && $uri === '/play') {
            return (new GameController($playGame))->play();
        }

        if ($method === 'POST' && $uri === '/guess') {
            return (new GameController($playGame))->guess();
        }

        return [
            'status' => 404,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['error' => 'Not Found']),
        ];
    }
}