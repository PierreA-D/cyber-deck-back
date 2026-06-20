<?php

namespace App\Handler\SpellEffect;


use App\Dto\SpellEffect\SpellEffectResponseDto;
use App\Repository\SpellEffectRepository;

final class GetSpellEffectHandler
{
    public function __construct(private readonly SpellEffectRepository $spellEffectRepository)
    {
    }

    public function handle(int $id): ?SpellEffectResponseDto
    {
        $spellEffect = $this->spellEffectRepository->find($id);
        if (null === $spellEffect) {
            return null;
        }

        return SpellEffectResponseDto::fromEntity($spellEffect);
    }
}
