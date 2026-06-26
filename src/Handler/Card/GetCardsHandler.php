<?php

namespace App\Handler\Card;

use App\Dto\Card\CardResponseDto;
use App\Entity\User;
use App\Repository\CardRepository;
use App\Repository\UserCardRepository;

final class GetCardsHandler
{
    public function __construct(
        private readonly CardRepository $cardRepository,
        private readonly UserCardRepository $userCardRepository
    )
    {
    }

    /**
     * @return list<CardResponseDto>
     */
    public function handle(): array
    {
        $cards = $this->cardRepository->findBy([], ['id' => 'ASC']);

        return array_map(
            static fn ($card): CardResponseDto => CardResponseDto::fromEntity($card),
            $cards,
        );
    }

    /**
     * @return list<CardResponseDto>
     */
    public function handleListCardByPlayer(User $player): array
    {
        $userCards = $this->userCardRepository->findBy(['player' => $player], ['id' => 'ASC']);

        return array_map(
            static fn ($card): CardResponseDto => CardResponseDto::fromEntity($card->getCard()),
            $userCards,
        );
    }
}
