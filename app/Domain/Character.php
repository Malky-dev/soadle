<?php

declare(strict_types=1);

namespace App\Domain;

final class Character
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $gender,
        public readonly int $firstAppearanceSeason,
        public readonly ?int $deathSeason,
        public readonly string $affiliation,
        public readonly ?string $imagePath
    ) {
    }

    public function survivesSeries(): bool
    {
        return $this->deathSeason === null;
    }

    public function resolvedImagePath(): string
    {
        return $this->imagePath ?? 'characters/default.png';
    }
}