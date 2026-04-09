<?php

declare(strict_types=1);

use App\Application\CharacterRepository;
use App\Application\CompareCharacters;
use App\Application\PlayGame;
use App\Domain\Character;

function buildCharacter(
    int $id,
    string $name,
    string $gender = 'male',
    int $firstAppearanceSeason = 1,
    ?int $deathSeason = null,
    string $affiliation = 'SAMCRO',
    ?string $imagePath = null
): Character {
    return new Character(
        id: $id,
        name: $name,
        gender: $gender,
        firstAppearanceSeason: $firstAppearanceSeason,
        deathSeason: $deathSeason,
        affiliation: $affiliation,
        imagePath: $imagePath
    );
}

function assertSame(mixed $expected, mixed $actual): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(sprintf(
            'Expected %s, got %s.',
            exportValue($expected),
            exportValue($actual)
        ));
    }
}

function assertCount(int $expectedCount, array $items): void
{
    $actualCount = count($items);

    if ($expectedCount !== $actualCount) {
        throw new RuntimeException(sprintf(
            'Expected count %d, got %d.',
            $expectedCount,
            $actualCount
        ));
    }
}

function assertTrue(bool $condition, string $message = 'Condition is false.'): void
{
    if ($condition !== true) {
        throw new RuntimeException($message);
    }
}

function exportValue(mixed $value): string
{
    return var_export($value, true);
}

final class InMemoryCharacterRepository implements CharacterRepository
{
    /**
     * @param list<Character> $characters
     */
    public function __construct(
        private array $characters
    ) {
    }

    public function findById(int $id): ?Character
    {
        foreach ($this->characters as $character) {
            if ($character->id === $id) {
                return $character;
            }
        }

        return null;
    }

    public function findAll(): array
    {
        return $this->characters;
    }
}

return [
    'PlayGame::start returns an initial playable state' => function (): void {
        $characters = [
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
        ];

        $useCase = new PlayGame(new InMemoryCharacterRepository($characters), new CompareCharacters());

        $state = $useCase->start();

        assertSame(null, $state['error']);
        assertSame(false, $state['isWin']);
        assertSame([], $state['attemptedIds']);
        assertCount(2, $state['availableCharacters']);
        assertTrue(in_array($state['targetId'], [1, 2], true), 'Expected a valid target id.');
    },

    'PlayGame::start returns an empty state when no characters exist' => function (): void {
        $useCase = new PlayGame(new InMemoryCharacterRepository([]), new CompareCharacters());

        $state = $useCase->start();

        assertSame(0, $state['targetId']);
        assertSame([], $state['attemptedIds']);
        assertSame([], $state['attempts']);
        assertSame([], $state['availableCharacters']);
        assertSame(false, $state['isWin']);
        assertSame('No characters available.', $state['error']);
    },

    'PlayGame::guess rejects invalid ids as bad requests' => function (): void {
        $characters = [
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
        ];

        $useCase = new PlayGame(new InMemoryCharacterRepository($characters), new CompareCharacters());

        $state = $useCase->guess(0, 2, []);

        assertSame(true, $state['badRequest']);
        assertSame(0, $state['targetId']);
        assertSame([], $state['attemptedIds']);
    },

    'PlayGame::guess records a valid attempt and removes it from available choices' => function (): void {
        $characters = [
            buildCharacter(
                id: 1,
                name: 'Jax Teller',
                gender: 'male',
                firstAppearanceSeason: 1,
                deathSeason: 7,
                affiliation: 'SAMCRO'
            ),
            buildCharacter(
                id: 2,
                name: 'Gemma Teller',
                gender: 'female',
                firstAppearanceSeason: 1,
                deathSeason: 7,
                affiliation: 'SAMCRO'
            ),
            buildCharacter(
                id: 3,
                name: 'Clay Morrow',
                gender: 'male',
                firstAppearanceSeason: 1,
                deathSeason: 6,
                affiliation: 'SAMCRO'
            ),
        ];

        $useCase = new PlayGame(new InMemoryCharacterRepository($characters), new CompareCharacters());

        $state = $useCase->guess(1, 2, []);

        assertSame(false, $state['badRequest']);
        assertSame([2], $state['attemptedIds']);
        assertSame(false, $state['isWin']);
        assertCount(1, $state['attempts']);
        assertCount(2, $state['availableCharacters']);
        assertSame('Gemma Teller', $state['attempts'][0]['character']->name);
        assertSame('different', $state['attempts'][0]['comparison']['gender']);
        assertSame('match', $state['attempts'][0]['comparison']['first_appearance_season']);
    },

    'PlayGame::guess returns a duplicate-attempt error without mutating the state' => function (): void {
        $characters = [
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
            buildCharacter(id: 3, name: 'Clay Morrow'),
        ];

        $useCase = new PlayGame(new InMemoryCharacterRepository($characters), new CompareCharacters());

        $state = $useCase->guess(1, 2, [2]);

        assertSame(false, $state['badRequest']);
        assertSame([2], $state['attemptedIds']);
        assertSame('This character has already been attempted.', $state['error']);
        assertCount(1, $state['attempts']);
        assertCount(2, $state['availableCharacters']);
    },

    'PlayGame::guess marks the game as won when the target is guessed' => function (): void {
        $characters = [
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
        ];

        $useCase = new PlayGame(new InMemoryCharacterRepository($characters), new CompareCharacters());

        $state = $useCase->guess(1, 1, []);

        assertSame(false, $state['badRequest']);
        assertSame(true, $state['isWin']);
        assertSame([1], $state['attemptedIds']);
        assertCount(1, $state['attempts']);
        assertCount(1, $state['availableCharacters']);
    },
];
