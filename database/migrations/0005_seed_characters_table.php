<?php

declare(strict_types=1);

return static function (\PDO $pdo): void {
    $statement = $pdo->query('SELECT COUNT(*) FROM characters');
    $count = (int) $statement->fetchColumn();

    if ($count > 0) {
        return;
    }

    $insert = $pdo->prepare(
        <<<SQL
        INSERT INTO characters (
            name,
            gender,
            first_appearance_season,
            death_season,
            affiliation,
            created_at
        ) VALUES (
            :name,
            :gender,
            :first_appearance_season,
            :death_season,
            :affiliation,
            :created_at
        )
        SQL
    );

    $now = date('Y-m-d H:i:s');

    $characters = [
        [
            'name' => 'Jax Teller',
            'gender' => 'male',
            'first_appearance_season' => 1,
            'death_season' => 7,
            'affiliation' => 'sons',
        ],
        [
            'name' => 'Clay Morrow',
            'gender' => 'male',
            'first_appearance_season' => 1,
            'death_season' => 6,
            'affiliation' => 'sons',
        ],
        [
            'name' => 'Tig Trager',
            'gender' => 'male',
            'first_appearance_season' => 1,
            'death_season' => null,
            'affiliation' => 'sons',
        ],
        [
            'name' => 'Gemma Teller Morrow',
            'gender' => 'female',
            'first_appearance_season' => 1,
            'death_season' => 7,
            'affiliation' => 'civilian',
        ],
        [
            'name' => 'Wayne Unser',
            'gender' => 'male',
            'first_appearance_season' => 1,
            'death_season' => 7,
            'affiliation' => 'police',
        ],
    ];

    foreach ($characters as $character) {
        $insert->bindValue(':name', $character['name'], \PDO::PARAM_STR);
        $insert->bindValue(':gender', $character['gender'], \PDO::PARAM_STR);
        $insert->bindValue(':first_appearance_season', $character['first_appearance_season'], \PDO::PARAM_INT);

        if ($character['death_season'] === null) {
            $insert->bindValue(':death_season', null, \PDO::PARAM_NULL);
        } else {
            $insert->bindValue(':death_season', $character['death_season'], \PDO::PARAM_INT);
        }

        $insert->bindValue(':affiliation', $character['affiliation'], \PDO::PARAM_STR);
        $insert->bindValue(':created_at', $now, \PDO::PARAM_STR);
        $insert->execute();
    }
};