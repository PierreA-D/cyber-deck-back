<?php

namespace App\Handler\Currency;

use App\Dto\Currency\CurrencyResponseDto;
use App\Entity\User;

final class GetCurrencyHandler
{
    public function handle(User $player): CurrencyResponseDto
    {
        $currency = $player->getUserCurrency();

        return new CurrencyResponseDto($currency?->getBalance() ?? 0);
    }
}
