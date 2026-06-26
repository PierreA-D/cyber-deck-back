<?php

namespace App\Handler\Booster;

use App\Dto\Booster\BoosterOpenDto;
use App\Repository\BoosterRepository;

final class BoostersOpenHandler
{
    public function __construct(private readonly BoosterRepository $boosterRepository)
    {
    }

    /**
     * @return list<BoosterOpenDto>
     */
    public function handle(): array
    {
        $boosters = $this->boosterRepository->findBy([], ['id' => 'ASC']);

        return array_map(
            static fn ($booster): BoosterOpenDto => BoosterOpenDto::fromEntity($booster),
            $boosters,
        );
    }
}
