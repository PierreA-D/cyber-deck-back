<?php

namespace App\Handler\Card;

use App\Dto\Card\CardResponseDto;
use App\Repository\CardRepository;

final class GetCardHandler
{
    public function __construct(private readonly CardRepository $cardRepository)
    {
    }

    public function handle(int $id): ?CardResponseDto
    {
        $card = $this->cardRepository->find($id);
        if (null === $card) {
            return null;
        }

        return CardResponseDto::fromEntity($card);
    }
}
