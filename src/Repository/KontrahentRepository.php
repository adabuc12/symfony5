<?php

namespace App\Repository;

use App\Entity\Kontrahent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Kontrahent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kontrahent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kontrahent[]    findAll()
 * @method Kontrahent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KontrahentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kontrahent::class);
    }

     /**
      * @return Kontrahent[] Returns an array of Kontrahent objects
      */

    public function findByNameField($value)
    {
         return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Kontrahent
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
