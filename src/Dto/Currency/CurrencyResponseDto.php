<?php

namespace App\Dto\Currency;

final class CurrencyResponseDto
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
