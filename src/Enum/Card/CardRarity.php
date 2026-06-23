<?php

namespace App\Enum\Card;

enum CardRarity: string
{
    case Common    = 'common';
    case Rare      = 'rare';
    case Legendary = 'legendary';
}