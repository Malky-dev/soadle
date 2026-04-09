<?php

declare(strict_types=1);

use App\Application\CompareCharacters;

return [
    'CompareCharacters::execute compares exact string values' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(
            id: 1,
            name: 'Jax Teller',
            gender: 'male',
            firstAppearanceSeason: 1,
            deathSeason: 7,
            affiliation: 'SAMCRO'
        );

        $guess = buildCharacter(
            id: 2,
            name: 'Clay Morrow',
            gender: 'male',
            firstAppearanceSeason: 1,
            deathSeason: 7,
            affiliation: 'SAMCRO'
        );

        $result = $comparer->execute($target, $guess);

        assertSame('match', $result['gender']);
        assertSame('match', $result['first_appearance_season']);
        assertSame('match', $result['death_season']);
        assertSame('match', $result['affiliation']);
    },

    'CompareCharacters::execute returns different for different string values' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(
            id: 1,
            name: 'Target',
            gender: 'male',
            affiliation: 'SAMCRO'
        );

        $guess = buildCharacter(
            id: 2,
            name: 'Guess',
            gender: 'female',
            affiliation: 'MAYANS'
        );

        $result = $comparer->execute($target, $guess);

        assertSame('different', $result['gender']);
        assertSame('different', $result['affiliation']);
    },

    'CompareCharacters::execute returns higher when guessed season is lower than target' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', firstAppearanceSeason: 4);
        $guess = buildCharacter(id: 2, name: 'Guess', firstAppearanceSeason: 2);

        $result = $comparer->execute($target, $guess);

        assertSame('higher', $result['first_appearance_season']);
    },

    'CompareCharacters::execute returns lower when guessed season is higher than target' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', firstAppearanceSeason: 2);
        $guess = buildCharacter(id: 2, name: 'Guess', firstAppearanceSeason: 5);

        $result = $comparer->execute($target, $guess);

        assertSame('lower', $result['first_appearance_season']);
    },

    'CompareCharacters::execute returns different when nullable value is compared to null' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', deathSeason: null);
        $guess = buildCharacter(id: 2, name: 'Guess', deathSeason: 6);

        $result = $comparer->execute($target, $guess);

        assertSame('different', $result['death_season']);
    },

    'CompareCharacters::execute returns match when nullable numeric values are equal' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', deathSeason: 6);
        $guess = buildCharacter(id: 2, name: 'Guess', deathSeason: 6);

        $result = $comparer->execute($target, $guess);

        assertSame('match', $result['death_season']);
    },

    'CompareCharacters::execute returns match when both nullable values are null' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', deathSeason: null);
        $guess = buildCharacter(id: 2, name: 'Guess', deathSeason: null);

        $result = $comparer->execute($target, $guess);

        assertSame('match', $result['death_season']);
    },

    'CompareCharacters::execute compares nullable numeric values with direction' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', deathSeason: 7);
        $guess = buildCharacter(id: 2, name: 'Guess', deathSeason: 5);

        $result = $comparer->execute($target, $guess);

        assertSame('higher', $result['death_season']);
    },

    'CompareCharacters::execute returns lower when guessed nullable season is higher than target' => function (): void {
        $comparer = new CompareCharacters();

        $target = buildCharacter(id: 1, name: 'Target', deathSeason: 4);
        $guess = buildCharacter(id: 2, name: 'Guess', deathSeason: 7);

        $result = $comparer->execute($target, $guess);

        assertSame('lower', $result['death_season']);
    },
];