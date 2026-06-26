<?php

namespace App\Dto\Balance;

final class BalanceResponseDto
{
    public function __construct(
        public readonly int $balance,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'balance' => $this->balance,
        ];
    }
}
