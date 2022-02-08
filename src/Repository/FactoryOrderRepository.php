<?php

namespace App\Repository;

use App\Entity\FactoryOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FactoryOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactoryOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactoryOrder[]    findAll()
 * @method FactoryOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactoryOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactoryOrder::class);
    }

    // /**
    //  * @return FactoryOrder[] Returns an array of FactoryOrder objects
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
    public function findOneBySomeField($value): ?FactoryOrder
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
