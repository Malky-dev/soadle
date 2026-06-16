<?php

declare(strict_types=1);

namespace App\Http\ViewModel;

use App\Domain\Character;

final class GameViewModelFactory
{
    /**
     * Build the complete view model required by the game template.
     *
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
    public function fromState(array $state): array
    {
        return [
            'targetId' => $state['targetId'],
            'attemptedIdsValue' => implode(',', $state['attemptedIds']),
            'attempts' => $this->buildAttemptRows($state['attempts']),
            'availableCharacters' => $this->buildCharacterOptions(
                $state['availableCharacters']
            ),
            'isWin' => $state['isWin'],
            'error' => $state['error'],
        ];
    }

    /**
     * Convert gameplay attempts into a template-friendly structure.
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
     * Build the lightweight data required by the character search component.
     *
     * @param list<Character> $characters
     * @return list<array{
     *     id: int,
     *     name: string,
     *     normalizedName: string
     * }>
     */
    private function buildCharacterOptions(array $characters): array
    {
        return array_map(
            static fn (Character $character): array => [
                'id' => $character->id,
                'name' => $character->name,
                'normalizedName' => mb_strtolower(
                    $character->name,
                    'UTF-8'
                ),
            ],
            $characters
        );
    }
}