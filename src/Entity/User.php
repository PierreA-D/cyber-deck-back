<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Deck>
     */
    #[ORM\OneToMany(targetEntity: Deck::class, mappedBy: 'player')]
    private Collection $deck;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'player')]
    private Collection $game;

    #[ORM\OneToOne(mappedBy: 'player', cascade: ['persist', 'remove'])]
    private ?UserCurrency $userCurrency = null;

    #[ORM\OneToMany(targetEntity: UserCard::class, mappedBy: 'player', cascade: ['persist', 'remove'])]
    private Collection $userCards;

    public function __construct()
    {
        $this->deck = new ArrayCollection();
        $this->game = new ArrayCollection();
        $this->userCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Deck>
     */
    public function getDeck(): Collection
    {
        return $this->deck;
    }

    public function addDeck(Deck $deck): static
    {
        if (!$this->deck->contains($deck)) {
            $this->deck->add($deck);
            $deck->setPlayer($this);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): static
    {
        if ($this->deck->removeElement($deck)) {
            // set the owning side to null (unless already changed)
            if ($deck->getPlayer() === $this) {
                $deck->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGame(): Collection
    {
        return $this->game;
    }

    public function addGame(Game $game): static
    {
        if (!$this->game->contains($game)) {
            $this->game->add($game);
            $game->setPlayer($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->game->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPlayer() === $this) {
                $game->setPlayer(null);
            }
        }

        return $this;
    }

    public function getUserCurrency(): ?UserCurrency
    {
        return $this->userCurrency;
    }

    public function setUserCurrency(UserCurrency $userCurrency): static
    {
        if ($userCurrency->getPlayer() !== $this) {
            $userCurrency->setPlayer($this);
        }

        $this->userCurrency = $userCurrency;

        return $this;
    }

    /**
     * @return Collection<int, UserCard>
     */
    public function getUserCards(): Collection
    {
        return $this->userCards;
    }

    public function addUserCards(UserCard $userCards): static
    {
        if (!$this->userCards->contains($userCards)) {
            $this->userCards->add($userCards);
            $userCards->setPlayer($this);
        }

        return $this;
    }

    public function removeUserCards(UserCard $userCards): static
    {
        if ($this->userCards->removeElement($userCards)) {
            // set the owning side to null (unless already changed)
            if ($userCards->getPlayer() === $this) {
                $userCards->setPlayer(null);
            }
        }

        return $this;
    }
}
