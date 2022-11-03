<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\HistoricalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoricalData>
 *
 * @method HistoricalData|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoricalData|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoricalData[]    findAll()
 * @method HistoricalData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoricalDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoricalData::class);
    }

    public function add(HistoricalData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HistoricalData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getDataBySymbol(string $value)
    {
         return $this->createQueryBuilder('h')
            ->where('h.symbol = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }
}
