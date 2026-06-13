<?php

namespace App\Handler\Deck;

use App\Dto\Deck\DeckResponseDto;
use App\Dto\Deck\DeckUpsertDto;
use App\Entity\User;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateDeckHandler
{
    public function __construct(
        private readonly DeckRepository $deckRepository,
        private readonly CardRepository $cardRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(User $user, int $id, DeckUpsertDto $dto): ?DeckResponseDto
    {
        $deck = $this->deckRepository->findOneBy(['id' => $id, 'player' => $user]);
        if (null === $deck) {
            return null;
        }

        $cards = $this->cardRepository->findBy(['id' => $dto->cardIds]);
        if (count($cards) !== count($dto->cardIds)) {
            throw new \InvalidArgumentException('Some cardIds do not exist.');
        }

        $deck->setName($dto->name);
        $deck->setColor($dto->color);
        $deck->setIsActive($dto->isActive);

        foreach ($deck->getCards()->toArray() as $existingCard) {
            $deck->removeCard($existingCard);
        }

        foreach ($cards as $card) {
            $deck->addCard($card);
        }

        $this->entityManager->flush();

        return DeckResponseDto::fromEntity($deck);
    }
}
