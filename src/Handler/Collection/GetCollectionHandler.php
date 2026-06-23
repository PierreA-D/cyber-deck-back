<?php

namespace App\Handler\Collection;

use App\Dto\Collection\CollectionItemDto;
use App\Entity\User;
use App\Entity\UserCard;
use App\Repository\UserCardRepository;

final class GetCollectionHandler
{
    public function __construct(private readonly UserCardRepository $userCardRepository)
    {
    }

    /**
     * @return list<CollectionItemDto>
     */
    public function handle(User $player): array
    {
        $userCards = $this->userCardRepository->findBy(['player' => $player], ['id' => 'ASC']);

        return array_map(
            static fn (UserCard $userCard): CollectionItemDto => CollectionItemDto::fromEntity($userCard),
            $userCards,
        );
    }
}
