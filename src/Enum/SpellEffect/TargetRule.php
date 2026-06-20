<?php

namespace App\Enum\SpellEffect;

enum TargetRule: string
{
    case LOWEST_HP = 'lowest_hp';
    case HIGHEST_ATTACK = 'highest_attack';
    case SELF_CHAMPION = 'self_champion';
    case RANDOM = 'random';
}