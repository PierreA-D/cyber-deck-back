<?php

namespace App\Handler\Balance;

use App\Dto\Balance\BalanceResponseDto;
use App\Entity\User;

final class GetBalanceHandler
{
    public function handle(User $player): BalanceResponseDto
    {
        $currency = $player->getUserCurrency();

        return new BalanceResponseDto($currency?->getBalance() ?? 0);
    }
}
