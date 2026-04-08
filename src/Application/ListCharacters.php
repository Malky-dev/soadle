<?php

declare(strict_types=1);

namespace App\Application;

final class ListCharacters
{
    public function __construct(
        private readonly CharacterRepository $characterRepository
    ) {
    }

    public function handle(): array
    {
        return $this->characterRepository->findAll();
    }
}