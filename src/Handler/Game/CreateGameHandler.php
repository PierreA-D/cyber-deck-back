<?php

namespace App\Handler\Game;

use App\Dto\Game\GameCreateDto;
use App\Dto\Game\GameResponseDto;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class CreateGameHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function handle(User $user, GameCreateDto $dto): GameResponseDto
    {
        $game = new Game();
        $game->setResult($dto->result);
        $game->setTurnsCount($dto->turnsCount);
        $game->setPlayedAt($dto->playedAt ?? new \DateTimeImmutable());
        $game->setPlayer($user);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return GameResponseDto::fromEntity($game);
    }
}
