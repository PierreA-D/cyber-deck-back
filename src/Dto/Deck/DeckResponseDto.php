<?php

namespace App\Dto\Deck;

use App\Entity\Deck;

final class DeckResponseDto
{
    /**
     * @param list<\App\Dto\Deck\DeckCardDto> $cards
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $color,
        public readonly bool $isActive,
        public readonly string $createdAt,
        public readonly array $cards,
    ) {
    }

    public static function fromEntity(Deck $deck): self
    {
        $cards = [];
        foreach ($deck->getCards() as $card) {
            $cards[] = \App\Dto\Deck\DeckCardDto::fromEntity($card);
        }

        return new self(
            $deck->getId() ?? 0,
            $deck->getName() ?? '',
            $deck->getColor() ?? '',
            $deck->isActive() ?? false,
            $deck->getCreatedAt()?->format(DATE_ATOM) ?? '',
            $cards,
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
            'color' => $this->color,
            'isActive' => $this->isActive,
            'createdAt' => $this->createdAt,
            'cards' => array_map(
                static fn (\App\Dto\Deck\DeckCardDto $card): array => $card->toArray(),
                $this->cards,
            ),
        ];
    }
}
