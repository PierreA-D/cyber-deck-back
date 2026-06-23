<?php

namespace App\Dto\Booster;

use App\Dto\Card\CardResponseDto;
use App\Entity\Booster;
use App\Entity\Card;

final class BoosterOpenDto
{
    /**
     * @param list<CardResponseDto> $cards
     */
    public function __construct(
        public readonly int $boosterId,
        public readonly string $boosterName,
        public readonly int $remainingBalance,
        public readonly array $cards,
    ) {
    }

    /**
     * @param list<Card> $cards
     */
    public static function fromResult(Booster $booster, int $remainingBalance, array $cards): self
    {
        return new self(
            $booster->getId() ?? 0,
            $booster->getName() ?? '',
            $remainingBalance,
            array_map(
                static fn (Card $card): CardResponseDto => CardResponseDto::fromEntity($card),
                array_values($cards),
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'boosterId' => $this->boosterId,
            'boosterName' => $this->boosterName,
            'remainingBalance' => $this->remainingBalance,
            'cards' => array_map(
                static fn (CardResponseDto $card): array => $card->toArray(),
                $this->cards,
            ),
        ];
    }
}
