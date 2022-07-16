<?php

namespace App\Repository;

use App\Entity\CurrencyExchangeOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyExchangeOperation>
 *
 * @method CurrencyExchangeOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyExchangeOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyExchangeOperation[]    findAll()
 * @method CurrencyExchangeOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyExchangeOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyExchangeOperation::class);
    }

    public function add(CurrencyExchangeOperation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CurrencyExchangeOperation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param int $limit
     * @param string $orderBy
     * @return array
     */
    public function getResultingExchangeRateWithLimit
    (
        int    $limit,
        string $orderBy = 'DESC'
    ): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.resultingExchangeRate')
            ->setMaxResults($limit)
            ->orderBy('c.id', $orderBy)
            ->getQuery()
            ->useQueryCache(false)
            ->getResult();
    }


//    /**
//     * @return CurrencyExchangeOperationType[] Returns an array of CurrencyExchangeOperationType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CurrencyExchangeOperationType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
