<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\PlayGame;
use App\Domain\Character;

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
        $targetId = $this->parseRequiredPositiveInteger($_POST['target_id'] ?? null);
        $guessId = $this->parseRequiredPositiveInteger($_POST['guess_id'] ?? null);
    
        if ($targetId === null || $guessId === null) {
            return $this->badRequest();
        }
    
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

    private function parseRequiredPositiveInteger(mixed $raw): ?int
    {
        if (!is_string($raw) && !is_int($raw)) {
            return null;
        }

        $value = trim((string) $raw);

        if ($value === '' || !ctype_digit($value)) {
            return null;
        }

        $id = (int) $value;

        return $id > 0 ? $id : null;
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
            $rawId = trim($part);
    
            if ($rawId === '' || !ctype_digit($rawId)) {
                continue;
            }
    
            $id = (int) $rawId;
    
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
     *         character: Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<Character>,
     *     isWin: bool,
     *     error: ?string
     * } $state
     */
    private function renderGame(array $state): array
    {
        return $this->render('game', $this->buildGameViewModel($state));
    }

    /**
     * @param array{
     *     targetId: int,
     *     attemptedIds: list<int>,
     *     attempts: list<array{
     *         character: Character,
     *         comparison: array<string, string>
     *     }>,
     *     availableCharacters: list<Character>,
     *     isWin: bool,
     *     error: ?string
     * } $state
     * @return array{
     *     targetId: int,
     *     attemptedIdsValue: string,
     *     attempts: list<array{
     *         characterName: string,
     *         gender: string,
     *         firstAppearanceSeason: string,
     *         deathSeason: string,
     *         affiliation: string
     *     }>,
     *     availableCharacters: list<array{
     *         id: int,
     *         name: string,
     *         normalizedName: string
     *     }>,
     *     isWin: bool,
     *     error: ?string
     * }
     */
    private function buildGameViewModel(array $state): array
    {
        return [
            'targetId' => $state['targetId'],
            'attemptedIdsValue' => implode(',', $state['attemptedIds']),
            'attempts' => $this->buildAttemptRows($state['attempts']),
            'availableCharacters' => $this->buildAvailableCharacterOptions($state['availableCharacters']),
            'isWin' => $state['isWin'],
            'error' => $state['error'],
        ];
    }

    /**
     * Prepare previous attempts for display so the view does not depend on domain objects.
     *
     * @param list<array{
     *     character: Character,
     *     comparison: array<string, string>
     * }> $attempts
     * @return list<array{
     *     characterName: string,
     *     gender: string,
     *     firstAppearanceSeason: string,
     *     deathSeason: string,
     *     affiliation: string
     * }>
     */
    private function buildAttemptRows(array $attempts): array
    {
        return array_map(
            static fn (array $attempt): array => [
                'characterName' => $attempt['character']->name,
                'gender' => $attempt['comparison']['gender'],
                'firstAppearanceSeason' => $attempt['comparison']['first_appearance_season'],
                'deathSeason' => $attempt['comparison']['death_season'],
                'affiliation' => $attempt['comparison']['affiliation'],
            ],
            $attempts
        );
    }

    /**
     * Build the lightweight character data required by the search UI.
     *
     * @param list<Character> $characters
     * @return list<array{
     *     id: int,
     *     name: string,
     *     normalizedName: string
     * }>
     */
    private function buildAvailableCharacterOptions(array $characters): array
    {
        return array_map(
            static fn (Character $character): array => [
                'id' => $character->id,
                'name' => $character->name,
                'normalizedName' => mb_strtolower($character->name, 'UTF-8'),
            ],
            $characters
        );
    }
}