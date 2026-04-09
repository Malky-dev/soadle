<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Character;

final class PlayGame
{
    public function __construct(
        private CharacterRepository $repo,
        private CompareCharacters $comparer
    ) {
    }

    /**
     * @return array{
     *     targetId: int,
     *     attemptedIds: list<int>,
     *     attempts: list<array{
     *         character: Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<Character>,
     *     isWin: bool,
     *     error: ?string
     * }
     */
    public function start(): array
    {
        $characters = $this->repo->findAll();

        if ($characters === []) {
            return [
                'targetId' => 0,
                'attemptedIds' => [],
                'attempts' => [],
                'availableCharacters' => [],
                'isWin' => false,
                'error' => 'No characters available.',
            ];
        }

        $target = $characters[array_rand($characters)];

        return [
            'targetId' => $target->id,
            'attemptedIds' => [],
            'attempts' => [],
            'availableCharacters' => $characters,
            'isWin' => false,
            'error' => null,
        ];
    }

    /**
     * @param list<int> $attemptedIds
     * @return array{
     *     targetId: int,
     *     attemptedIds: list<int>,
     *     attempts: list<array{
     *         character: Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<Character>,
     *     isWin: bool,
     *     error: ?string,
     *     badRequest: bool
     * }
     */
    public function guess(int $targetId, int $guessId, array $attemptedIds): array
    {
        if ($targetId <= 0 || $guessId <= 0) {
            return $this->badRequestState();
        }

        // Load the character set once so the full game state can be rebuilt
        // in memory without repeated repository calls.
        $characters = $this->repo->findAll();

        if ($characters === []) {
            return [
                'targetId' => 0,
                'attemptedIds' => [],
                'attempts' => [],
                'availableCharacters' => [],
                'isWin' => false,
                'error' => 'No characters available.',
                'badRequest' => false,
            ];
        }

        $charactersById = $this->indexCharactersById($characters);
        $target = $charactersById[$targetId] ?? null;
        $guess = $charactersById[$guessId] ?? null;

        if ($target === null || $guess === null) {
            return $this->badRequestState();
        }

        if (in_array($guessId, $attemptedIds, true)) {
            return $this->buildGameState(
                target: $target,
                characters: $characters,
                charactersById: $charactersById,
                attemptedIds: $attemptedIds,
                error: 'This character has already been attempted.'
            );
        }

        $attemptedIds[] = $guessId;

        return $this->buildGameState(
            target: $target,
            characters: $characters,
            charactersById: $charactersById,
            attemptedIds: $attemptedIds
        );
    }

    /**
     * @param list<Character> $characters
     * @param array<int, Character> $charactersById
     * @param list<int> $attemptedIds
     * @return array{
     *     targetId: int,
     *     attemptedIds: list<int>,
     *     attempts: list<array{
     *         character: Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<Character>,
     *     isWin: bool,
     *     error: ?string,
     *     badRequest: bool
     * }
     */
    private function buildGameState(
        Character $target,
        array $characters,
        array $charactersById,
        array $attemptedIds,
        ?string $error = null
    ): array {
        return [
            'targetId' => $target->id,
            'attemptedIds' => $attemptedIds,
            'attempts' => $this->buildAttempts($target, $attemptedIds, $charactersById),
            'availableCharacters' => $this->buildAvailableCharacters($characters, $attemptedIds),
            'isWin' => in_array($target->id, $attemptedIds, true),
            'error' => $error,
            'badRequest' => false,
        ];
    }

    /**
     * Build an in-memory lookup table so guess validation and attempt rendering
     * can resolve characters by id without hitting the repository again.
     *
     * @param list<Character> $characters
     * @return array<int, Character>
     */
    private function indexCharactersById(array $characters): array
    {
        $charactersById = [];

        foreach ($characters as $character) {
            $charactersById[$character->id] = $character;
        }

        return $charactersById;
    }

    /**
     * @param list<int> $attemptedIds
     * @param array<int, Character> $charactersById
     * @return list<array{
     *     character: Character,
     *     comparison: array<string, string>
     * }>
     */
    private function buildAttempts(Character $target, array $attemptedIds, array $charactersById): array
    {
        $attempts = [];

        foreach ($attemptedIds as $attemptedId) {
            $character = $charactersById[$attemptedId] ?? null;

            if ($character === null) {
                continue;
            }

            $attempts[] = [
                'character' => $character,
                'comparison' => $this->comparer->execute($target, $character),
            ];
        }

        return $attempts;
    }

    /**
     * @param list<Character> $characters
     * @param list<int> $attemptedIds
     * @return list<Character>
     */
    private function buildAvailableCharacters(array $characters, array $attemptedIds): array
    {
        $availableCharacters = [];

        foreach ($characters as $character) {
            if (in_array($character->id, $attemptedIds, true)) {
                continue;
            }

            $availableCharacters[] = $character;
        }

        return $availableCharacters;
    }

    /**
     * @return array{
     *     targetId: int,
     *     attemptedIds: list<int>,
     *     attempts: list<array{
     *         character: Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<Character>,
     *     isWin: bool,
     *     error: ?string,
     *     badRequest: bool
     * }
     */
    private function badRequestState(): array
    {
        return [
            'targetId' => 0,
            'attemptedIds' => [],
            'attempts' => [],
            'availableCharacters' => [],
            'isWin' => false,
            'error' => null,
            'badRequest' => true,
        ];
    }
}