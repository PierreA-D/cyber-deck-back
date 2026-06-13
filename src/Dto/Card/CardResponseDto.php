<?php

namespace App\Dto\Card;

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
    ) {
    }

    public static function fromEntity(Card $card): self
    {
        return new self(
            $card->getId() ?? 0,
            $card->getName() ?? '',
            $card->getType() ?? '',
            $card->getColor() ?? '',
            $card->getAttack(),
            $card->getHp(),
            $card->getHeal(),
            $card->getDescription(),
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
        ];
    }
}
