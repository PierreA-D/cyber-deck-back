<?php

namespace App\Handler\Deck;

use App\Entity\User;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteDeckHandler
{
    public function __construct(
        private readonly DeckRepository $deckRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(User $user, int $id): bool
    {
        $deck = $this->deckRepository->findOneBy(['id' => $id, 'player' => $user]);
        if (null === $deck) {
            return false;
        }

        $this->entityManager->remove($deck);
        $this->entityManager->flush();

        return true;
    }
}
