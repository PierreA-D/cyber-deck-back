<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $result = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $playedAt = null;

    #[ORM\Column]
    private ?int $turnsCount = null;

    #[ORM\ManyToOne(inversedBy: 'game')]
    private ?User $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getPlayedAt(): ?\DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeImmutable $playedAt): static
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    public function getTurnsCount(): ?int
    {
        return $this->turnsCount;
    }

    public function setTurnsCount(int $turnsCount): static
    {
        $this->turnsCount = $turnsCount;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }
}
