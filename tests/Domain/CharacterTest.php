<?php

declare(strict_types=1);

return [
    'Character::survivesSeries returns true when death season is null' => function (): void {
        $character = buildCharacter(id: 1, name: 'Jax Teller', deathSeason: null);

        assertTrue($character->survivesSeries());
    },

    'Character::survivesSeries returns false when death season is set' => function (): void {
        $character = buildCharacter(id: 1, name: 'Jax Teller', deathSeason: 7);

        assertFalse($character->survivesSeries());
    },

    'Character::resolvedImagePath returns default path when image path is null' => function (): void {
        $character = buildCharacter(id: 1, name: 'Jax Teller', imagePath: null);

        assertSame('characters/default.png', $character->resolvedImagePath());
    },

    'Character::resolvedImagePath returns explicit image path when provided' => function (): void {
        $character = buildCharacter(
            id: 1,
            name: 'Jax Teller',
            imagePath: 'characters/jax-teller.webp'
        );

        assertSame('characters/jax-teller.webp', $character->resolvedImagePath());
    },

    'Character keeps provided scalar values' => function (): void {
        $character = buildCharacter(
            id: 42,
            name: 'Opie Winston',
            gender: 'male',
            firstAppearanceSeason: 1,
            deathSeason: 5,
            affiliation: 'SAMCRO',
            imagePath: 'characters/opie-winston.webp'
        );

        assertSame(42, $character->id);
        assertSame('Opie Winston', $character->name);
        assertSame('male', $character->gender);
        assertSame(1, $character->firstAppearanceSeason);
        assertSame(5, $character->deathSeason);
        assertSame('SAMCRO', $character->affiliation);
        assertSame('characters/opie-winston.webp', $character->imagePath);
    },
];