<?php

namespace App\Handler\Game;

use App\Dto\Game\GameResponseDto;
use App\Entity\User;
use App\Repository\GameRepository;

final class GetGameHandler
{
    public function __construct(private readonly GameRepository $gameRepository)
    {
    }

    public function handle(User $user, int $id): ?GameResponseDto
    {
        $game = $this->gameRepository->findOneBy(['id' => $id, 'player' => $user]);
        if (null === $game) {
            return null;
        }

        return GameResponseDto::fromEntity($game);
    }
}
