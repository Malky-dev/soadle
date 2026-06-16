<?php

declare(strict_types=1);

namespace App\Http\Input;

final class GameInputParser
{
    /**
     * Parse and validate the guess form payload.
     *
     * @param array<string, mixed> $post
     */
    public function fromPost(array $post): ?GameInput
    {
        $targetId = $this->parseRequiredPositiveInteger($post['target_id'] ?? null);
        $guessId = $this->parseRequiredPositiveInteger($post['guess_id'] ?? null);

        if ($targetId === null || $guessId === null) {
            return null;
        }

        return new GameInput(
            targetId: $targetId,
            guessId: $guessId,
            attemptedIds: $this->parseAttemptedIds((string) ($post['attempted_ids'] ?? ''))
        );
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
}