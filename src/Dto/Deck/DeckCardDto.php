<?php

namespace App\Dto\Deck;

use App\Entity\Card;

final class DeckCardDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly string $color,
    ) {
    }

    public static function fromEntity(Card $card): self
    {
        return new self(
            $card->getId() ?? 0,
            $card->getName() ?? '',
            $card->getType() ?? '',
            $card->getColor() ?? '',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'color' => $this->color,
        ];
    }
}
