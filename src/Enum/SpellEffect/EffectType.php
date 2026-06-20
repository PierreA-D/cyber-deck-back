<?php

namespace App\Enum\SpellEffect;

enum EffectType: string
{
    case BUFF_ATTACK = 'buff_attack';
    case BUFF_HP = 'buff_hp';
    case DEBUFF_ATTACK = 'debuff_attack';
    case HEAL = 'heal';
    case STUN = 'stun';
    case SHIELD = 'shield';
}