<?php

namespace App\Enum\Card;

enum CardType: string
{
    case WARRIOR   = 'warrior';
    case DEFENDER  = 'defender';
    case HEALER    = 'healer';
    case LEGEND  = 'legend';
    case ASSASSIN = 'assassin';
    case IMPLANT   = 'implant';
    case OVERCLOCK = 'overclock'; 
    case PROTOCOLE = 'protocole';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isSpell(): bool
    {
        return in_array($this, [self::IMPLANT, self::OVERCLOCK, self::PROTOCOLE], true);
    }
}