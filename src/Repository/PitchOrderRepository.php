<?php

namespace App\Repository;

use App\Entity\PitchOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PitchOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method PitchOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method PitchOrder[]    findAll()
 * @method PitchOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PitchOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PitchOrder::class);
    }

    // /**
    //  * @return PitchOrder[] Returns an array of PitchOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PitchOrder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
