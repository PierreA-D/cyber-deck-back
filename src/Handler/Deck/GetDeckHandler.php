<?php

namespace App\Handler\Deck;

use App\Dto\Deck\DeckResponseDto;
use App\Entity\User;
use App\Repository\DeckRepository;

final class GetDeckHandler
{
    public function __construct(private readonly DeckRepository $deckRepository)
    {
    }

    public function handle(User $user, int $id): ?DeckResponseDto
    {
        $deck = $this->deckRepository->findOneBy(['id' => $id, 'player' => $user]);
        if (null === $deck) {
            return null;
        }

        return DeckResponseDto::fromEntity($deck);
    }
}
