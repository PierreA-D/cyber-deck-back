<?php

namespace App\Dto\SpellEffect;

use App\Entity\SpellEffect;

final class SpellEffectResponseDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $targetType,
        public readonly string $targetSide,
        public readonly string $targetMode,
        public readonly ?string $targetRule,
        public readonly string $effectType,
        public readonly int $value,
        public readonly ?int $duration,
        public readonly ?int $cardId,
    ) {
    }

    public static function fromEntity(SpellEffect $spellEffect): self
    {
        return new self(
            $spellEffect->getId() ?? 0,
            $spellEffect->getTargetType()?->value ?? '',
            $spellEffect->getTargetSide()?->value ?? '',
            $spellEffect->getTargetMode()?->value ?? '',
            $spellEffect->getTargetRule()?->value,
            $spellEffect->getEffectType()?->value ?? '',
            $spellEffect->getValue() ?? 0,
            $spellEffect->getDuration(),
            $spellEffect->getCard()?->getId(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'targetType' => $this->targetType,
            'targetSide' => $this->targetSide,
            'targetMode' => $this->targetMode,
            'targetRule' => $this->targetRule,
            'effectType' => $this->effectType,
            'value' => $this->value,
            'duration' => $this->duration,
            'cardId' => $this->cardId,
        ];
    }
}
