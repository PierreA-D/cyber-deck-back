<?php

namespace App\Entity;

use App\Enum\SpellEffect\EffectType;
use App\Enum\SpellEffect\TargetMode;
use App\Enum\SpellEffect\TargetRule;
use App\Enum\SpellEffect\TargetSide;
use App\Enum\SpellEffect\TargetType;
use App\Repository\SpellEffectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpellEffectRepository::class)]
class SpellEffect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: TargetType::class)]
    private ?TargetType $targetType = null;

    #[ORM\Column(enumType: TargetSide::class)]
    private ?TargetSide $targetSide = null;

    #[ORM\Column(enumType: TargetMode::class)]
    private ?TargetMode $targetMode = null;

    #[ORM\Column(nullable: true, enumType: TargetRule::class)]
    private ?TargetRule $targetRule = null;

    #[ORM\Column(enumType: EffectType::class)]
    private ?EffectType $effectType = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\OneToOne(inversedBy: 'spellEffect', cascade: ['persist', 'remove'])]
    private ?Card $card = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTargetType(): ?TargetType
    {
        return $this->targetType;
    }

    public function setTargetType(TargetType $targetType): static
    {
        $this->targetType = $targetType;

        return $this;
    }

    public function getTargetSide(): ?TargetSide
    {
        return $this->targetSide;
    }

    public function setTargetSide(TargetSide $targetSide): static
    {
        $this->targetSide = $targetSide;

        return $this;
    }

    public function getTargetMode(): ?TargetMode
    {
        return $this->targetMode;
    }

    public function setTargetMode(TargetMode $targetMode): static
    {
        $this->targetMode = $targetMode;

        return $this;
    }

    public function getTargetRule(): ?TargetRule
    {
        return $this->targetRule;
    }

    public function setTargetRule(?TargetRule $targetRule): static
    {
        $this->targetRule = $targetRule;

        return $this;
    }

    public function getEffectType(): ?EffectType
    {
        return $this->effectType;
    }

    public function setEffectType(EffectType $effectType): static
    {
        $this->effectType = $effectType;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): static
    {
        $this->card = $card;

        return $this;
    }
}
