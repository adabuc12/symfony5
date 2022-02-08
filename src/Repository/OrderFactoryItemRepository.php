<?php

namespace App\Repository;

use App\Entity\OrderFactoryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderFactoryItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderFactoryItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderFactoryItem[]    findAll()
 * @method OrderFactoryItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderFactoryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderFactoryItem::class);
    }

    // /**
    //  * @return OrderFactoryItem[] Returns an array of OrderFactoryItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderFactoryItem
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
