<?php

namespace App\Handler\SpellEffect;

use App\Dto\SpellEffect\SpellEffectResponseDto;
use App\Repository\SpellEffectRepository;

final class GetSpellEffectsHandler
{
    public function __construct(private readonly SpellEffectRepository $spellEffectRepository)
    {
    }

    /**
     * @return list<SpellEffectResponseDto>
     */
    public function handle(): array
    {
        $spellEffects = $this->spellEffectRepository->findBy([], ['id' => 'ASC']);

        return array_map(
            static fn ($spellEffect): SpellEffectResponseDto => SpellEffectResponseDto::fromEntity($spellEffect),
            $spellEffects,
        );
    }
}
