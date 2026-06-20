<?php

namespace App\Dto\Card;

use App\Dto\SpellEffect\SpellEffectResponseDto;
use App\Entity\Card;

final class CardResponseDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly string $color,
        public readonly ?int $attack,
        public readonly ?int $hp,
        public readonly ?int $heal,
        public readonly ?string $description,
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
            $card->getAttack(),
            $card->getHp(),
            $card->getHeal(),
            $card->getDescription(),
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
            'attack' => $this->attack,
            'hp' => $this->hp,
            'heal' => $this->heal,
            'description' => $this->description,
            'spellEffect' => $this->spellEffect?->toArray(),
        ];
    }
}
