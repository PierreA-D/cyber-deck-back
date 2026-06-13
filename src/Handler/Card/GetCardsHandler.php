<?php

namespace App\Handler\Card;

use App\Dto\Card\CardResponseDto;
use App\Repository\CardRepository;

final class GetCardsHandler
{
    public function __construct(private readonly CardRepository $cardRepository)
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
}
