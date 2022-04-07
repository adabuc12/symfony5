<?php

namespace App\Repository;

use App\Entity\FastDeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FastDeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method FastDeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method FastDeal[]    findAll()
 * @method FastDeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FastDealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FastDeal::class);
    }

    // /**
    //  * @return FastDeal[] Returns an array of FastDeal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FastDeal
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
