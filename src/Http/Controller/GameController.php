<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\PlayGame;

final class GameController extends AbstractController
{
    public function __construct(
        private PlayGame $playGame
    ) {
    }

    public function play(): array
    {
        $state = $this->playGame->start();

        if ($state['targetId'] === 0) {
            return $this->text('No characters available.', 500);
        }

        return $this->renderGame($state);
    }

    public function guess(): array
    {
        $targetId = (int) ($_POST['target_id'] ?? 0);
        $guessId = (int) ($_POST['guess_id'] ?? 0);
        $attemptedIds = $this->parseAttemptedIds((string) ($_POST['attempted_ids'] ?? ''));

        $state = $this->playGame->guess($targetId, $guessId, $attemptedIds);

        if ($state['badRequest'] === true) {
            return $this->badRequest();
        }

        if ($state['targetId'] === 0) {
            return $this->text('No characters available.', 500);
        }

        return $this->renderGame($state);
    }

    /**
     * Rebuild the attempted id list from the hidden form field while filtering
     * out invalid or duplicated values.
     *
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
     * @param array{
     *     targetId: int,
     *     attemptedIds: list<int>,
     *     attempts: list<array{
     *         character: \App\Domain\Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<\App\Domain\Character>,
     *     isWin: bool,
     *     error: ?string
     * } $state
     */
    private function renderGame(array $state): array
    {
        return $this->render('game', [
            'targetId' => $state['targetId'],
            'attemptedIdsValue' => implode(',', $state['attemptedIds']),
            'attempts' => $state['attempts'],
            'availableCharacters' => $state['availableCharacters'],
            'isWin' => $state['isWin'],
            'error' => $state['error'],
        ]);
    }
}