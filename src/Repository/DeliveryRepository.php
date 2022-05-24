<?php

namespace App\Repository;

use App\Entity\Delivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @method Delivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delivery[]    findAll()
 * @method Delivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Delivery::class);
    }

    /**
     * @return Delivery[] Returns an array of Delivery objects
     */
    public function findAllByOrder($value) {
        return $this->createQueryBuilder('d')
                        ->join('d.delivery_order', 'o')
                        ->join('o.kontrahent', 'k')
                        ->andWhere('o.id = :val')
                        ->setParameter('val', $value->getId())
                        ->orderBy('d.id', 'ASC')
                        ->getQuery()
                        ->getResult()
        ;
    }
    
    /**
     * @return Delivery[] Returns an array of Delivery objects
     */
    public function findAllByDate($startDate, $endDate) {
        return $this->createQueryBuilder('d')
                        ->andWhere('d.delivery_date > :start')
                        ->andWhere('d.delivery_date < :end')
                        ->setParameter('start', new DateTime($startDate))
                        ->setParameter('end', new DateTime($endDate))
                        ->getQuery()
                        ->getResult()
        ;
    }

    /*
      public function findOneBySomeField($value): ?Delivery
      {
      return $this->createQueryBuilder('d')
      ->andWhere('d.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
