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

        $target = $this->repo->findById($targetId);
        $guess = $this->repo->findById($guessId);

        if ($target === null || $guess === null) {
            return $this->badRequest();
        }

        if (in_array($guessId, $attemptedIds, true)) {
            return $this->renderCurrentGameState(
                target: $target,
                attemptedIds: $attemptedIds,
                error: 'This character has already been attempted.'
            );
        }

        $attemptedIds[] = $guessId;

        return $this->renderCurrentGameState($target, $attemptedIds);
    }

    /**
     * @param list<int> $attemptedIds
     */
    private function renderCurrentGameState(
        Character $target,
        array $attemptedIds,
        ?string $error = null
    ): array {
        $attempts = $this->buildAttempts($target, $attemptedIds);
        $availableCharacters = $this->buildAvailableCharacters($attemptedIds);
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
     * @param list<int> $attemptedIds
     * @return list<array{
     *     character: Character,
     *     comparison: array<string, string>
     * }>
     */
    private function buildAttempts(Character $target, array $attemptedIds): array
    {
        $attempts = [];

        foreach ($attemptedIds as $attemptedId) {
            $character = $this->repo->findById($attemptedId);

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
     * @param list<int> $attemptedIds
     * @return list<Character>
     */
    private function buildAvailableCharacters(array $attemptedIds): array
    {
        $characters = $this->repo->findAll();
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