<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\CharacterRepository;
use App\Domain\Character;
use PDO;

final class CharacterSqlRepository implements CharacterRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function findById(int $id): ?Character
    {
        $statement = $this->pdo->prepare(
            <<<SQL
            SELECT
                id,
                name,
                gender,
                first_appearance_season,
                death_season,
                affiliation,
                image_path
            FROM characters
            WHERE id = :id
            LIMIT 1
            SQL
        );

        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapRowToCharacter($row);
    }

    public function findAll(): array
    {
        $statement = $this->pdo->query(
            <<<SQL
            SELECT
                id,
                name,
                gender,
                first_appearance_season,
                death_season,
                affiliation,
                image_path
            FROM characters
            ORDER BY name ASC
            SQL
        );

        $rows = $statement->fetchAll();

        $characters = [];

        foreach ($rows as $row) {
            $characters[] = $this->mapRowToCharacter($row);
        }

        return $characters;
    }

    private function mapRowToCharacter(array $row): Character
    {
        return new Character(
            id: (int) $row['id'],
            name: $row['name'],
            gender: $row['gender'],
            firstAppearanceSeason: (int) $row['first_appearance_season'],
            deathSeason: $row['death_season'] === null ? null : (int) $row['death_season'],
            affiliation: $row['affiliation'],
            imagePath: $row['image_path']
        );
    }
}