<?php

namespace App\Enum\SpellEffect;

enum TargetType: string
{
    case SINGLE_CARD = 'single_card';
    case BOARD = 'board';
    case CHAMPION = 'champion';
}