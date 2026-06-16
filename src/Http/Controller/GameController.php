<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\PlayGame;
use App\Domain\Character;
use App\Http\Input\GameInputParser;
use App\Http\ViewModel\GameViewModelFactory;

final class GameController extends AbstractController
{
    public function __construct(
        private PlayGame $playGame,
        private GameViewModelFactory $viewModelFactory,
        private GameInputParser $gameInputParser
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
        $input = $this->gameInputParser->fromPost($_POST);

        if ($input === null) {
            return $this->badRequest();
        }

        $state = $this->playGame->guess(
            $input->targetId,
            $input->guessId,
            $input->attemptedIds
        );

        if ($state['badRequest'] === true) {
            return $this->badRequest();
        }

        if ($state['targetId'] === 0) {
            return $this->text('No characters available.', 500);
        }

        return $this->renderGame($state);
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
        return $this->render('game', $this->viewModelFactory->fromState($state));
    }
}