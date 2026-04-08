<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\CharacterRepository;
use App\Application\CompareCharacters;
use App\Domain\Character;

final class GameController extends AbstractController
{
    public function __construct(
        private CharacterRepository $repo,
        private CompareCharacters $comparer
    ) {
    }

    public function play(): array
    {
        $characters = $this->repo->findAll();

        if ($characters === []) {
            return $this->text('No characters available.', 500);
        }

        $target = $characters[array_rand($characters)];

        return $this->renderGame(
            targetId: $target->id,
            attemptedIds: [],
            attempts: [],
            availableCharacters: $characters,
            isWin: false,
            error: null
        );
    }

    public function guess(): array
    {
        $targetId = (int) ($_POST['target_id'] ?? 0);
        $guessId = (int) ($_POST['guess_id'] ?? 0);
        $attemptedIds = $this->parseAttemptedIds((string) ($_POST['attempted_ids'] ?? ''));

        if ($targetId <= 0 || $guessId <= 0) {
            return $this->badRequest();
        }

        // Load the full character list once so the current request can rebuild
        // the game state without issuing repeated repository reads.
        $characters = $this->repo->findAll();

        if ($characters === []) {
            return $this->text('No characters available.', 500);
        }

        $charactersById = $this->indexCharactersById($characters);
        $target = $charactersById[$targetId] ?? null;
        $guess = $charactersById[$guessId] ?? null;

        if ($target === null || $guess === null) {
            return $this->badRequest();
        }

        if (in_array($guessId, $attemptedIds, true)) {
            return $this->renderCurrentGameState(
                target: $target,
                characters: $characters,
                charactersById: $charactersById,
                attemptedIds: $attemptedIds,
                error: 'This character has already been attempted.'
            );
        }

        $attemptedIds[] = $guessId;

        return $this->renderCurrentGameState(
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
     */
    private function renderCurrentGameState(
        Character $target,
        array $characters,
        array $charactersById,
        array $attemptedIds,
        ?string $error = null
    ): array {
        $attempts = $this->buildAttempts($target, $attemptedIds, $charactersById);
        $availableCharacters = $this->buildAvailableCharacters($characters, $attemptedIds);
        $isWin = in_array($target->id, $attemptedIds, true);

        return $this->renderGame(
            targetId: $target->id,
            attemptedIds: $attemptedIds,
            attempts: $attempts,
            availableCharacters: $availableCharacters,
            isWin: $isWin,
            error: $error
        );
    }

    /**
     * Rebuild the attempted id list from the hidden form field while filtering
     * out invalid or duplicated values.
     *
     * @param list<int> $attemptedIds
     * @return list<int>
     */
    private function parseAttemptedIds(string $raw): array
    {
        if ($raw === '') {
            return [];
        }

        $parts = explode(',', $raw);
        $attemptedIds = [];

        foreach ($parts as $part) {
            $id = (int) trim($part);

            if ($id <= 0 || in_array($id, $attemptedIds, true)) {
                continue;
            }

            $attemptedIds[] = $id;
        }

        return $attemptedIds;
    }

    /**
     * Build an in-memory lookup table so later steps can resolve characters
     * by id in constant time without hitting the repository again.
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
     * Filter out already attempted characters from the preloaded list instead
     * of re-querying the repository for the remaining options.
     *
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
     * @param list<int> $attemptedIds
     * @param list<array{
     *     character: Character,
     *     comparison: array<string, string>
     * }> $attempts
     * @param list<Character> $availableCharacters
     */
    private function renderGame(
        int $targetId,
        array $attemptedIds,
        array $attempts,
        array $availableCharacters,
        bool $isWin,
        ?string $error
    ): array {
        return $this->render('game', [
            'targetId' => $targetId,
            'attemptedIdsValue' => implode(',', $attemptedIds),
            'attempts' => $attempts,
            'availableCharacters' => $availableCharacters,
            'isWin' => $isWin,
            'error' => $error,
        ]);
    }
}