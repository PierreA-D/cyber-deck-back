<?php

namespace App\Dto\Deck;

use App\Dto\SpellEffect\SpellEffectResponseDto;
use App\Entity\Card;

final class DeckCardDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly string $color,
        public readonly ?SpellEffectResponseDto $spellEffect,
    ) {
    }

    public static function fromEntity(Card $card): self
    {
        $spellEffect = $card->getSpellEffect();

        return new self(
            $card->getId() ?? 0,
            $card->getName() ?? '',
            $card->getType()?->value ?? '',
            $card->getColor() ?? '',
            null !== $spellEffect ? SpellEffectResponseDto::fromEntity($spellEffect) : null,
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
            'spellEffect' => $this->spellEffect?->toArray(),
        ];
    }
}
