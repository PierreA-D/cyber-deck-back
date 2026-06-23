<?php

namespace App\Repository;

use App\Entity\Card;
use App\Enum\Card\CardRarity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    /**
     * @return list<int>
     */
    public function findIdsByRarity(CardRarity $rarity): array
    {
        $rows = $this->createQueryBuilder('c')
            ->select('c.id')
            ->andWhere('c.rarity = :rarity')
            ->setParameter('rarity', $rarity)
            ->getQuery()
            ->getScalarResult();

        return array_map(static fn (array $row): int => (int) $row['id'], $rows);
    }
}
