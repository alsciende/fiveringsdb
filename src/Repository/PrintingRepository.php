<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Printing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Printing>
 */
class PrintingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Printing::class);
    }

    /**
     * @return Printing[] Returns an array of Printing objects with the given card_id
     */
    public function findCardVersions(string $card_id): array
    {
        return $this->createQueryBuilder('c')
            ->where('card_id = :cardId')
            ->setParameter('cardId', $card_id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<string, mixed> $criterias
     *
     * @return Printing[] Returns an array of Card objects which have a name that contains the provided string
     */
    public function findByPartialField(array $criterias): array
    {
        $qb = $this->createQueryBuilder('c');

        foreach ($criterias as $key => $value) {
            if (in_array($key, ['card_id', 'pack_id', 'flavor_text'], true)) {
                $qb->andWhere($qb->expr()->like("LOWER(c.{$key})", "LOWER(:{$key})"))
                    ->setParameter($key, "%{$value}%");
            } else {
                $qb->andWhere($qb->expr()->eq("c.{$key}", ":{$key}"))
                    ->setParameter($key, $value);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
