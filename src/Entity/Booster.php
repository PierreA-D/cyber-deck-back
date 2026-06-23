<?php

namespace App\Entity;

use App\Repository\BoosterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoosterRepository::class)]
class Booster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\Column]
    private ?int $cardCount = null;

    #[ORM\Column(type: 'json')]
    private array $rarityWeights = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCardCount(): ?int
    {
        return $this->cardCount;
    }

    public function setCardCount(int $cardCount): static
    {
        $this->cardCount = $cardCount;

        return $this;
    }

    public function getRarityWeights(): array
    {
        return $this->rarityWeights;
    }

    public function setRarityWeights(array $rarityWeights): static
    {
        $this->rarityWeights = $rarityWeights;

        return $this;
    }
}
