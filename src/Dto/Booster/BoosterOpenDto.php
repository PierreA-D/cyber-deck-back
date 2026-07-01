<?php

namespace App\Dto\Booster;

use App\Dto\Card\CardResponseDto;
use App\Entity\Booster;
use App\Entity\Card;
use App\Dto\Extension\ExtensionResponseDto;

final class BoosterOpenDto
{
    /**
     * @param list<CardResponseDto> $cards
     */
    public function __construct(
        public readonly int $boosterId,
        public readonly string $boosterName,
        public readonly int $remainingBalance,
        public readonly int $cost,
        public readonly ExtensionResponseDto $extension,
    ) {
    }

    public static function fromEntity(Booster $booster): self
    {
        return new self(
            $booster->getId() ?? 0,
            $booster->getName() ?? '',
            0,
            $booster->getCost() ?? 0,
            null !== $booster->getExtension() ? ExtensionResponseDto::fromEntity($booster->getExtension()) : null,
        );
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
            $booster->getCost() ?? 0,
            $booster->getExtension()
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->boosterId,
            'name' => $this->boosterName,
            'remainingBalance' => $this->remainingBalance,
            'cost' => $this->cost,
            'extension' => $this->extension?->toArray(),
        ];
    }
}
