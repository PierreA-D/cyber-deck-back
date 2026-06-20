<?php

namespace App\Enum\SpellEffect;

enum TargetSide: string
{
    case ALLY = 'ally';
    case ENEMY = 'enemy';
}