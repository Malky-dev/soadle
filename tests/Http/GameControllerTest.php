<?php

declare(strict_types=1);

use App\Application\CharacterRepository;
use App\Application\CompareCharacters;
use App\Application\PlayGame;
use App\Domain\Character;
use App\Http\Controller\GameController;
use App\Http\ViewModel\GameViewModelFactory;

final class GameControllerTestCharacterRepository implements CharacterRepository
{
    /**
     * @param list<Character> $characters
     */
    public function __construct(
        private array $characters
    ) {
    }

    public function findById(int $id): ?Character
    {
        foreach ($this->characters as $character) {
            if ($character->id === $id) {
                return $character;
            }
        }

        return null;
    }

    public function findAll(): array
    {
        return $this->characters;
    }
}

/**
 * @param list<Character> $characters
 */
function buildGameController(array $characters): GameController
{
    return new GameController(
        new PlayGame(
            new GameControllerTestCharacterRepository($characters),
            new CompareCharacters()
        ),
        new GameViewModelFactory()
    );  
}

return [
    'GameController::guess rejects malformed target ids before the use case' => function (): void {
        $_POST = [
            'target_id' => '12abc',
            'guess_id' => '2',
            'attempted_ids' => '',
        ];

        $controller = buildGameController([
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
        ]);

        $response = $controller->guess();

        assertSame(400, $response['status']);
        assertSame('Bad Request', $response['body']);

        $_POST = [];
    },

    'GameController::guess rejects malformed guess ids before the use case' => function (): void {
        $_POST = [
            'target_id' => '1',
            'guess_id' => '2abc',
            'attempted_ids' => '',
        ];

        $controller = buildGameController([
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
        ]);

        $response = $controller->guess();

        assertSame(400, $response['status']);
        assertSame('Bad Request', $response['body']);

        $_POST = [];
    },

    'GameController::guess ignores malformed attempted ids' => function (): void {
        $_POST = [
            'target_id' => '1',
            'guess_id' => '3',
            'attempted_ids' => '2,2abc,-4,0,,999foo,2',
        ];

        $controller = buildGameController([
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
            buildCharacter(id: 3, name: 'Clay Morrow'),
        ]);

        $response = $controller->guess();

        assertSame(200, $response['status']);

        assertTrue(
            str_contains($response['body'], 'name="attempted_ids"'),
            'Expected attempted ids hidden field to be rendered.'
        );

        assertTrue(
            str_contains($response['body'], 'value="2,3"'),
            'Expected malformed attempted ids to be ignored.'
        );

        $_POST = [];
    },

    'GameController::guess displays duplicate attempt error feedback' => function (): void {
        $_POST = [
            'target_id' => '1',
            'guess_id' => '2',
            'attempted_ids' => '2',
        ];

        $controller = buildGameController([
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Clay Morrow'),
        ]);

        $response = $controller->guess();

        assertSame(200, $response['status']);
        assertTrue(
            str_contains($response['body'], 'role="alert"'),
            'Expected error feedback to be exposed as an alert.'
        );
        assertTrue(
            str_contains($response['body'], 'This character has already been attempted.'),
            'Expected duplicate attempt error to be displayed.'
        );

        $_POST = [];
    },
];