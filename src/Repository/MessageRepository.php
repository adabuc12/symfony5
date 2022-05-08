<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return Message[] Returns an array of Message objects
     */
    public function findByCart($value) {
        return $this->createQueryBuilder('m')
                        ->andWhere('m.cart = :val')
                        ->setParameter('val', $value)
                        ->orderBy('m.id', 'DESC')
                        ->setMaxResults(10)
                        ->getQuery()
                        ->getResult()
        ;
    }

    /**
     * @return Message[] Returns an array of Message objects
     */
    public function findByCartAndType($value, $status, $type) {
        return $this->createQueryBuilder('m')
                        ->andWhere('m.cart = :val')
                        ->setParameter('val', $value)
                        ->andWhere('m.status = :vall')
                        ->setParameter('vall', $status)
                        ->andWhere('m.type = :val2')
                        ->setParameter('val2', $type)
                        ->orderBy('m.id', 'DESC')
                        ->setMaxResults(10)
                        ->getQuery()
                        ->getResult()
        ;
    }

    /*
      public function findOneBySomeField($value): ?Message
      {
      return $this->createQueryBuilder('m')
      ->andWhere('m.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
