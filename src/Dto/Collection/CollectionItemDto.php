<?php

namespace App\Dto\Collection;

use App\Dto\Card\CardResponseDto;
use App\Entity\UserCard;

final class CollectionItemDto
{
    public function __construct(
        public readonly CardResponseDto $card,
        public readonly int $quantity,
    ) {
    }

    public static function fromEntity(UserCard $userCard): self
    {
        return new self(
            CardResponseDto::fromEntity($userCard->getCard()),
            $userCard->getQuantity() ?? 0,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'card' => $this->card->toArray(),
            'quantity' => $this->quantity,
        ];
    }
}
