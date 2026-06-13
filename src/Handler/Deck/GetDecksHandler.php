<?php

namespace App\Handler\Deck;

use App\Dto\Deck\DeckResponseDto;
use App\Entity\User;
use App\Repository\DeckRepository;

final class GetDecksHandler
{
    public function __construct(private readonly DeckRepository $deckRepository)
    {
    }

    /**
     * @return list<DeckResponseDto>
     */
    public function handle(User $user): array
    {
        $decks = $this->deckRepository->findBy(['player' => $user], ['createdAt' => 'DESC']);

        return array_map(
            static fn ($deck): DeckResponseDto => DeckResponseDto::fromEntity($deck),
            $decks,
        );
    }
}
