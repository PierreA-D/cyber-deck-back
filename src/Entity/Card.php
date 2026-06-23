<?php

namespace App\Entity;

use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: CardType::class)]
    private ?CardType $type = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?int $attack = null;

    #[ORM\Column(nullable: true)]
    private ?int $hp = null;

    #[ORM\Column(nullable: true)]
    private ?int $heal = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Deck>
     */
    #[ORM\ManyToMany(targetEntity: Deck::class, mappedBy: 'cards')]
    private Collection $decks;

    #[ORM\OneToOne(mappedBy: 'card', cascade: ['persist', 'remove'])]
    private ?SpellEffect $spellEffect = null;

    #[ORM\Column(enumType: CardRarity::class)]
    private ?CardRarity $rarity = CardRarity::Common;

    public function __construct()
    {
        $this->decks = new ArrayCollection();
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

    public function getType(): ?CardType
    {
        return $this->type;
    }

    public function setType(CardType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(?int $attack): static
    {
        $this->attack = $attack;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getHeal(): ?int
    {
        return $this->heal;
    }

    public function setHeal(?int $heal): static
    {
        $this->heal = $heal;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Deck>
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(Deck $deck): static
    {
        if (!$this->decks->contains($deck)) {
            $this->decks->add($deck);
            $deck->addCard($this);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): static
    {
        if ($this->decks->removeElement($deck)) {
            $deck->removeCard($this);
        }

        return $this;
    }

    public function getSpellEffect(): ?SpellEffect
    {
        return $this->spellEffect;
    }

    public function setSpellEffect(?SpellEffect $spellEffect): static
    {
        if ($spellEffect === null && $this->spellEffect !== null) {
            $this->spellEffect->setCard(null);
        }

        if ($spellEffect !== null && $spellEffect->getCard() !== $this) {
            $spellEffect->setCard($this);
        }

        $this->spellEffect = $spellEffect;

        return $this;
    }

    public function getRarity(): ?CardRarity
    {
        return $this->rarity;
    }

    public function setRarity(CardRarity $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }
}
