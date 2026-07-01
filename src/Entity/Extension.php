<?php

namespace App\Entity;

use App\Repository\ExtensionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExtensionRepository::class)]
class Extension
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Card>
     */
    #[ORM\OneToMany(targetEntity: Card::class, mappedBy: 'extension')]
    private Collection $card;

    /**
     * @var Collection<int, Booster>
     */
    #[ORM\OneToMany(targetEntity: Booster::class, mappedBy: 'extension')]
    private Collection $booster;

    public function __construct()
    {
        $this->card = new ArrayCollection();
        $this->booster = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Card>
     */
    public function getCard(): Collection
    {
        return $this->card;
    }

    public function addCard(Card $card): static
    {
        if (!$this->card->contains($card)) {
            $this->card->add($card);
            $card->setExtension($this);
        }

        return $this;
    }

    public function removeCard(Card $card): static
    {
        if ($this->card->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getExtension() === $this) {
                $card->setExtension(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booster>
     */
    public function getBooster(): Collection
    {
        return $this->booster;
    }

    public function addBooster(Booster $booster): static
    {
        if (!$this->booster->contains($booster)) {
            $this->booster->add($booster);
            $booster->setExtension($this);
        }

        return $this;
    }

    public function removeBooster(Booster $booster): static
    {
        if ($this->booster->removeElement($booster)) {
            // set the owning side to null (unless already changed)
            if ($booster->getExtension() === $this) {
                $booster->setExtension(null);
            }
        }

        return $this;
    }
}
