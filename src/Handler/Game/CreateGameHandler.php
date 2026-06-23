<?php

namespace App\Handler\Game;

use App\Dto\Game\GameCreateDto;
use App\Dto\Game\GameResponseDto;
use App\Entity\Game;
use App\Entity\User;
use App\Entity\UserCurrency;
use Doctrine\ORM\EntityManagerInterface;

final class CreateGameHandler
{
    private const REWARD_WIN = 100;
    private const REWARD_LOSS = 0;

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

        $this->rewardPlayer($user, $dto->result);

        return GameResponseDto::fromEntity($game);
    }

    private function rewardPlayer(User $user, string $result): void
    {
        $reward = 'win' === $result ? self::REWARD_WIN : self::REWARD_LOSS;

        $currency = $user->getUserCurrency();
        if (null === $currency) {
            $currency = new UserCurrency();
            $currency->setPlayer($user);
            $currency->setBalance(0);
        }

        $currency->setBalance($currency->getBalance() + $reward);

        $this->entityManager->persist($currency);
        $this->entityManager->flush();
    }
}
