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
        // SAMCRO
        ['name' => 'Jackson "Jax" Teller', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'samcro'],
        ['name' => 'Clarence "Clay" Morrow', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 6, 'affiliation' => 'samcro'],
        ['name' => 'Alexander "Tig" Trager', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'samcro'],
        ['name' => 'Filip "Chibs" Telford', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'samcro'],
        ['name' => 'Juan Carlos "Juice" Ortiz', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'samcro'],
        ['name' => 'Robert "Bobby Elvis" Munson', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'samcro'],
        ['name' => 'Harry "Opie" Winston', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 5, 'affiliation' => 'samcro'],
        ['name' => 'Piermont "Piney" Winston', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 4, 'affiliation' => 'samcro'],
        ['name' => 'Happy Lowman', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'samcro'],
        ['name' => 'Kip "Half-Sack" Epps', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 2, 'affiliation' => 'samcro'],
        ['name' => 'Herman Kozik', 'gender' => 'male', 'first_appearance_season' => 3, 'death_season' => 4, 'affiliation' => 'samcro'],
        ['name' => 'Montez', 'gender' => 'male', 'first_appearance_season' => 6, 'death_season' => null, 'affiliation' => 'samcro'],
        ['name' => 'Frankie Diamonds', 'gender' => 'male', 'first_appearance_season' => 5, 'death_season' => 6, 'affiliation' => 'samcro'],
        ['name' => 'Otto "Big Otto" Delaney', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 6, 'affiliation' => 'samcro'],
        ['name' => 'George "Rat Boy" Skogstorm', 'gender' => 'male', 'first_appearance_season' => 5, 'death_season' => null, 'affiliation' => 'samcro'],

        // SAMBEL
        ['name' => 'Keith McGee', 'gender' => 'male', 'first_appearance_season' => 3, 'death_season' => 3, 'affiliation' => 'sambel'],
        ['name' => 'Liam O\'Neill', 'gender' => 'male', 'first_appearance_season' => 3, 'death_season' => 3, 'affiliation' => 'sambel'],

        // SAMDINO
        ['name' => 'Les Packer', 'gender' => 'male', 'first_appearance_season' => 6, 'death_season' => null, 'affiliation' => 'samdino'],

        // INDIAN HILLS
        ['name' => 'Jury White', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'indian_hills'],

        // MAYANS
        ['name' => 'Marcus Alvarez', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'mayans'],
        ['name' => 'Esai Alvarez', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 2, 'affiliation' => 'mayans'],
        ['name' => 'Oscar "El Oso" Ramos', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'mayans'],

        // IRA
        ['name' => 'Jimmy O\'Phelan', 'gender' => 'male', 'first_appearance_season' => 2, 'death_season' => null, 'affiliation' => 'ira'],
        ['name' => 'Cameron Hayes', 'gender' => 'male', 'first_appearance_season' => 3, 'death_season' => 3, 'affiliation' => 'ira'],
        ['name' => 'Edmond Hayes', 'gender' => 'male', 'first_appearance_season' => 3, 'death_season' => 3, 'affiliation' => 'ira'],
        ['name' => 'Galen O\'Shay', 'gender' => 'male', 'first_appearance_season' => 5, 'death_season' => 6, 'affiliation' => 'ira'],
        ['name' => 'Connor Malone', 'gender' => 'male', 'first_appearance_season' => 2, 'death_season' => 5, 'affiliation' => 'ira'],

        // CARTEL
        ['name' => 'Romeo Parada', 'gender' => 'male', 'first_appearance_season' => 4, 'death_season' => null, 'affiliation' => 'cartel'],
        ['name' => 'Luis Torres', 'gender' => 'male', 'first_appearance_season' => 4, 'death_season' => null, 'affiliation' => 'cartel'],

        // AB
        ['name' => 'Ron Tully', 'gender' => 'male', 'first_appearance_season' => 7, 'death_season' => null, 'affiliation' => 'ab'],
        ['name' => 'AJ Weston', 'gender' => 'male', 'first_appearance_season' => 2, 'death_season' => 2, 'affiliation' => 'ab'],
        ['name' => 'Ernest Darby', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'ab'],
        ['name' => 'Ethan Zobelle', 'gender' => 'male', 'first_appearance_season' => 2, 'death_season' => null, 'affiliation' => 'ab'],

        // OUTLAW
        ['name' => 'Damon Pope', 'gender' => 'male', 'first_appearance_season' => 5, 'death_season' => 5, 'affiliation' => 'outlaw'],
        ['name' => 'August Marks', 'gender' => 'male', 'first_appearance_season' => 6, 'death_season' => 7, 'affiliation' => 'outlaw'],
        ['name' => 'Laroy Wayne', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 4, 'affiliation' => 'outlaw'],
        ['name' => 'Henry Lin', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'outlaw'],
        ['name' => 'Georgie Caruso', 'gender' => 'male', 'first_appearance_season' => 2, 'death_season' => 2, 'affiliation' => 'outlaw'],
        ['name' => 'Barosky', 'gender' => 'male', 'first_appearance_season' => 6, 'death_season' => 7, 'affiliation' => 'outlaw'],
        ['name' => 'Tyler Yost', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'outlaw'],

        // POLICE
        ['name' => 'Wayne Unser', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'police'],
        ['name' => 'David Hale', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 3, 'affiliation' => 'police'],
        ['name' => 'Eli Roosevelt', 'gender' => 'male', 'first_appearance_season' => 4, 'death_season' => 7, 'affiliation' => 'police'],
        ['name' => 'June Stahl', 'gender' => 'female', 'first_appearance_season' => 2, 'death_season' => 3, 'affiliation' => 'police'],
        ['name' => 'Tyne Patterson', 'gender' => 'female', 'first_appearance_season' => 6, 'death_season' => null, 'affiliation' => 'police'],
        ['name' => 'Lee Toric', 'gender' => 'male', 'first_appearance_season' => 5, 'death_season' => 6, 'affiliation' => 'police'],
        ['name' => 'Josh Kohn', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 1, 'affiliation' => 'police'],
        ['name' => 'Lincoln Potter', 'gender' => 'male', 'first_appearance_season' => 4, 'death_season' => null, 'affiliation' => 'police'],
        ['name' => 'Althea Jarry', 'gender' => 'female', 'first_appearance_season' => 7, 'death_season' => null, 'affiliation' => 'police'],

        // OLD LADY
        ['name' => 'Gemma Teller Morrow', 'gender' => 'female', 'first_appearance_season' => 1, 'death_season' => 7, 'affiliation' => 'old_lady'],
        ['name' => 'Tara Knowles', 'gender' => 'female', 'first_appearance_season' => 1, 'death_season' => 6, 'affiliation' => 'old_lady'],
        ['name' => 'Wendy Case', 'gender' => 'female', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'old_lady'],
        ['name' => 'Lyla Winston', 'gender' => 'female', 'first_appearance_season' => 2, 'death_season' => null, 'affiliation' => 'old_lady'],
        ['name' => 'Luann Delaney', 'gender' => 'female', 'first_appearance_season' => 1, 'death_season' => 2, 'affiliation' => 'old_lady'],
        ['name' => 'Maureen Ashby', 'gender' => 'female', 'first_appearance_season' => 3, 'death_season' => null, 'affiliation' => 'old_lady'],
        ['name' => 'Donna Winston', 'gender' => 'female', 'first_appearance_season' => 1, 'death_season' => 1, 'affiliation' => 'old_lady'],

        // CIVILIAN
        ['name' => 'Trinity Ashby', 'gender' => 'female', 'first_appearance_season' => 3, 'death_season' => null, 'affiliation' => 'civilian'],
        ['name' => 'Elliot Oswald', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => null, 'affiliation' => 'civilian'],
        ['name' => 'Margaret Murphy', 'gender' => 'female', 'first_appearance_season' => 2, 'death_season' => null, 'affiliation' => 'civilian'],
        ['name' => 'Vincent "Venus" Noone', 'gender' => 'female', 'first_appearance_season' => 5, 'death_season' => null, 'affiliation' => 'civilian'],
        ['name' => 'Chuck Marstein', 'gender' => 'male', 'first_appearance_season' => 2, 'death_season' => null, 'affiliation' => 'civilian'],
        ['name' => 'Lowell Harland Jr.', 'gender' => 'male', 'first_appearance_season' => 1, 'death_season' => 2, 'affiliation' => 'civilian'],

        // BYZLAT
        ['name' => 'Nero Padilla', 'gender' => 'male', 'first_appearance_season' => 5, 'death_season' => null, 'affiliation' => 'byzlat'],
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