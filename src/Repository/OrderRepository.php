<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Finds carts that have not been modified since the given date.
     *
     * @param \DateTime $limitDate
     * @param int $limit
     *
     * @return int|mixed|string
     */
    public function findCartsNotModifiedSince(\DateTime $limitDate, int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :status')
            ->andWhere('o.updatedAt < :date')
            ->setParameter('status', Order::STATUS_CART)
            ->setParameter('date', $limitDate)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
    
     /**
      * @return Order[] Returns an array of Order objects
      */
    
    public function findByType($value, $user)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :val')
            ->andWhere('o.user = :user')
            ->setParameter('val', $value)
            ->setParameter('user', $user)
            ->orderBy('o.id', 'DESC')
        ;
    }
    
     /**
      * @return Order[] Returns an array of Order objects
      */
    
    public function findByFilters($number, $kontrahent, $date, $status, $deliveryDate, $user)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :val')
            ->andWhere('o.user = :user')
            ->andWhere('o.status = :status')
            ->andWhere('o.number = :status')
            ->andWhere('o.kontrahent = :status')
            ->setParameter('val', $value)
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->orderBy('o.id', 'DESC')
        ;
    }
}