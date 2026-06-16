<?php

declare(strict_types=1);

namespace App\Http\Input;

final readonly class GameInput
{
    /**
     * @param list<int> $attemptedIds
     */
    public function __construct(
        public int $targetId,
        public int $guessId,
        public array $attemptedIds
    ) {
    }
}