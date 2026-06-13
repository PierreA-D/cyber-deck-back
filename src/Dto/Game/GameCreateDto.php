<?php

namespace App\Dto\Game;

final class GameCreateDto
{
    public function __construct(
        public readonly string $result,
        public readonly int $turnsCount,
        public readonly ?\DateTimeImmutable $playedAt,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['result']) || !is_string($data['result']) || '' === trim($data['result'])) {
            throw new \InvalidArgumentException('Field "result" is required.');
        }

        if (!isset($data['turnsCount']) || !is_int($data['turnsCount'])) {
            throw new \InvalidArgumentException('Field "turnsCount" must be an integer.');
        }

        if ($data['turnsCount'] < 1) {
            throw new \InvalidArgumentException('Field "turnsCount" must be greater than 0.');
        }

        $playedAt = null;
        if (isset($data['playedAt'])) {
            if (!is_string($data['playedAt']) || '' === trim($data['playedAt'])) {
                throw new \InvalidArgumentException('Field "playedAt" must be an ISO-8601 datetime string.');
            }

            try {
                $playedAt = new \DateTimeImmutable($data['playedAt']);
            } catch (\Exception) {
                throw new \InvalidArgumentException('Field "playedAt" must be an ISO-8601 datetime string.');
            }
        }

        return new self(
            trim($data['result']),
            $data['turnsCount'],
            $playedAt,
        );
    }
}
