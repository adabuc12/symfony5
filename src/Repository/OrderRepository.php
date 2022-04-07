<?php

namespace App\Repository;

use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
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
    public function findCartsNotModifiedSince(\DateTime $limitDate, int $limit = 10): array {
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
    public function findByType($value, $user) {
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
    public function findByFilters($type, $number, $kontrahent, $date, $status, $deliveryDate, $user) {
        
        $date = explode(' - ', $date);
        $date1 = new DateTime($date[0]);
        $date2 = new DateTime($date[1]);
        $deliveryDate = explode(' - ',$deliveryDate);
        $deliveryDate1 = new DateTime($deliveryDate[0]);
        $deliveryDate2 = new DateTime($deliveryDate[1]);
        
        $query = $this->createQueryBuilder('o')->andWhere('o.status = :val')->setParameter('val', $type);
        $query->andWhere('o.createdAt BETWEEN :date1 AND :date2 ')->setParameter('date1', $date1)->setParameter('date2', $date2);
        $query->andWhere('o.delivery_date BETWEEN :deliveryDate1 AND :deliveryDate2 ')->setParameter('deliveryDate1', $deliveryDate1)->setParameter('deliveryDate2', $deliveryDate2);
        if(!empty($number)){
            $query->andWhere('o.number LIKE :number')->setParameter('number', '%'.$number.'%');
        }
        if(!empty($user)){
            $query->andWhere('o.user = :user')->setParameter('user', $user);
        }
        if(!empty($status)){
            $query->andWhere('o.type = :status')->setParameter('status', $status);
        }
        if(!empty($kontrahent)){
            $query->join('o.kontrahent','k');
            $query->andWhere('LOWER(k.name) LIKE LOWER(:kontrahent)') ->setParameter('kontrahent', '%'.$kontrahent.'%');
        }
        return $query->orderBy('o.id', 'DESC');
                
    }

}
