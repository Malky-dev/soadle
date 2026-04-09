<?php

declare(strict_types=1);

use App\Application\CharacterRepository;
use App\Application\ListCharacters;
use App\Domain\Character;

final class ListCharactersRepositoryStub implements CharacterRepository
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

return [
    'ListCharacters::handle returns all repository characters' => function (): void {
        $characters = [
            buildCharacter(id: 1, name: 'Jax Teller'),
            buildCharacter(id: 2, name: 'Gemma Teller'),
        ];

        $useCase = new ListCharacters(new ListCharactersRepositoryStub($characters));

        $result = $useCase->handle();

        assertCount(2, $result);
        assertSame('Jax Teller', $result[0]->name);
        assertSame('Gemma Teller', $result[1]->name);
    },
];