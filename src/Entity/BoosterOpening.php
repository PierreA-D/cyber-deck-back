<?php

namespace App\Entity;

use App\Repository\BoosterOpeningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoosterOpeningRepository::class)]
class BoosterOpening
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne(targetEntity: Booster::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Booster $booster = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $openedAt = null;

    #[ORM\Column(type: 'json')]
    private array $cardsObtained = [];

    public function __construct()
    {
        $this->openedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(User $player): static
    {
        $this->player = $player;
        return $this;
    }

    public function getBooster(): ?Booster
    {
        return $this->booster;
    }

    public function setBooster(Booster $booster): static
    {
        $this->booster = $booster;
        return $this;
    }

    public function getOpenedAt(): ?\DateTimeImmutable
    {
        return $this->openedAt;
    }

    public function getCardsObtained(): array
    {
        return $this->cardsObtained;
    }

    public function setCardsObtained(array $cardsObtained): static
    {
        $this->cardsObtained = $cardsObtained;
        return $this;
    }
}