<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CompanySymbols;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanySymbols>
 *
 * @method CompanySymbols|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanySymbols|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanySymbols[]    findAll()
 * @method CompanySymbols[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanySymbolsRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanySymbols::class);
    }

    /**
     * @param CompanySymbols $entity
     * @param bool $flush
     * @return void
     */
    public function add(CompanySymbols $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param CompanySymbols $entity
     * @param bool $flush
     * @return void
     */
    public function remove(CompanySymbols $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function truncate(): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $metaData = $this->getEntityManager()->getClassMetadata(CompanySymbols::class);

        $connection->executeQuery($platform->getTruncateTableSQL($metaData->getTableName(), true));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getCurrentLastModifyDate(): ?CompanySymbols
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
