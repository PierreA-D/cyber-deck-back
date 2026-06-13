<?php

namespace App\Handler\Deck;

use App\Dto\Deck\DeckResponseDto;
use App\Dto\Deck\DeckUpsertDto;
use App\Entity\Deck;
use App\Entity\User;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreateDeckHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CardRepository $cardRepository,
    ) {
    }

    public function handle(User $user, DeckUpsertDto $dto): DeckResponseDto
    {
        $cards = $this->cardRepository->findBy(['id' => $dto->cardIds]);
        if (count($cards) !== count($dto->cardIds)) {
            throw new \InvalidArgumentException('Some cardIds do not exist.');
        }

        $deck = new Deck();
        $deck->setName($dto->name);
        $deck->setColor($dto->color);
        $deck->setIsActive($dto->isActive);
        $deck->setCreatedAt(new \DateTimeImmutable());
        $deck->setPlayer($user);

        foreach ($cards as $card) {
            $deck->addCard($card);
        }

        $this->entityManager->persist($deck);
        $this->entityManager->flush();

        return DeckResponseDto::fromEntity($deck);
    }
}
