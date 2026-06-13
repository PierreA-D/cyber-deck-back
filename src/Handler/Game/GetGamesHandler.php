<?php

namespace App\Handler\Game;

use App\Dto\Game\GameResponseDto;
use App\Entity\User;
use App\Repository\GameRepository;

final class GetGamesHandler
{
    public function __construct(private readonly GameRepository $gameRepository)
    {
    }

    /**
     * @return list<GameResponseDto>
     */
    public function handle(User $user): array
    {
        $games = $this->gameRepository->findBy(['player' => $user], ['playedAt' => 'DESC']);

        return array_map(
            static fn ($game): GameResponseDto => GameResponseDto::fromEntity($game),
            $games,
        );
    }
}
