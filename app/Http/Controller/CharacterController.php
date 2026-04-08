<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\ListCharacters;
use App\Infrastructure\CharacterSqlRepository;
use App\Infrastructure\Database\Connection;

final class CharacterController
{
    public function index(): array
    {
        $pdo = Connection::make();
        $repository = new CharacterSqlRepository($pdo);
        $useCase = new ListCharacters($repository);

        $characters = $useCase->handle();

        ob_start();
        require dirname(__DIR__, 3) . '/resources/views/characters/index.php';
        $body = ob_get_clean();

        return [
            'status' => 200,
            'headers' => ['Content-Type' => 'text/html; charset=UTF-8'],
            'body' => $body === false ? '' : $body,
        ];
    }
}