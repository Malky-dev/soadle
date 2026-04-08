<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Character;

interface CharacterRepository
{
    public function findById(int $id): ?Character;

    public function findAll(): array;
}