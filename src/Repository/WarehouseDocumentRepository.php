<?php

namespace App\Repository;

use App\Entity\WarehouseDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WarehouseDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseDocument[]    findAll()
 * @method WarehouseDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseDocument::class);
    }

    // /**
    //  * @return WarehouseDocument[] Returns an array of WarehouseDocument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WarehouseDocument
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
