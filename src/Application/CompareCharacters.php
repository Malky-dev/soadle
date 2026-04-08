<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Character;

final class CompareCharacters
{
    /**
     * @return array<string, string>
     */
    public function execute(Character $target, Character $guess): array
    {
        return [
            'gender' => $this->compareValue($target->gender, $guess->gender),
            'first_appearance_season' => $this->compareNumber(
                $target->firstAppearanceSeason,
                $guess->firstAppearanceSeason
            ),
            'death_season' => $this->compareNullableNumber(
                $target->deathSeason,
                $guess->deathSeason
            ),
            'affiliation' => $this->compareValue($target->affiliation, $guess->affiliation),
        ];
    }

    private function compareValue(string $target, string $guess): string
    {
        return $target === $guess ? 'match' : 'different';
    }

    private function compareNumber(int $target, int $guess): string
    {
        if ($target === $guess) {
            return 'match';
        }

        if ($guess < $target) {
            return 'higher';
        }

        return 'lower';
    }

    private function compareNullableNumber(?int $target, ?int $guess): string
    {
        if ($target === $guess) {
            return 'match';
        }

        if ($target === null || $guess === null) {
            return 'different';
        }

        if ($guess < $target) {
            return 'higher';
        }

        return 'lower';
    }
}