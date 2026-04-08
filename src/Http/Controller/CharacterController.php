<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\ListCharacters;
use App\Infrastructure\CharacterSqlRepository;
use App\Infrastructure\Database\Connection;

final class CharacterController extends AbstractController
{
    public function index(): array
    {
        $repository = new CharacterSqlRepository(Connection::get());
        $useCase = new ListCharacters($repository);

        return $this->render('characters/index', [
            'characters' => $useCase->handle(),
        ]);
    }
}