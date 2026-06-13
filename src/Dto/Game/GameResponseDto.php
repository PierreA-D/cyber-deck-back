<?php

namespace App\Dto\Game;

use App\Entity\Game;

final class GameResponseDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $result,
        public readonly int $turnsCount,
        public readonly string $playedAt,
    ) {
    }

    public static function fromEntity(Game $game): self
    {
        return new self(
            $game->getId() ?? 0,
            $game->getResult() ?? '',
            $game->getTurnsCount() ?? 0,
            $game->getPlayedAt()?->format(DATE_ATOM) ?? '',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'result' => $this->result,
            'turnsCount' => $this->turnsCount,
            'playedAt' => $this->playedAt,
        ];
    }
}
