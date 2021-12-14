<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

     /**
      * @return Product[] Returns an array of Product objects
      */
    public function findByNameField($name,$factory)
    {
        $nameExploded = explode(' ' , $name);
        
                
        if(count($nameExploded) > 1 && count($nameExploded) < 3){
           if(!empty($factory)){
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
            ->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)')    
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$nameExploded[0].'%')
            ->setParameter('val3', '%'.$nameExploded[1].'%')
            ->setParameter('val1', '%'.$factory.'%')   
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       }else{
           return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$nameExploded[0].'%')
            ->setParameter('val3', '%'.$nameExploded[1].'%') 
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       }
        } elseif (count($nameExploded) > 2 && count($nameExploded) < 4){
           if(!empty($factory)){
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val4)')    
            ->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)')    
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$nameExploded[0].'%')
            ->setParameter('val3', '%'.$nameExploded[1].'%')
            ->setParameter('val4', '%'.$nameExploded[2].'%')
            ->setParameter('val1', '%'.$factory.'%')   
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       }else{
           return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val4)') 
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$nameExploded[0].'%')
            ->setParameter('val3', '%'.$nameExploded[1].'%') 
            ->setParameter('val4', '%'.$nameExploded[2].'%')
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       } 
        }elseif (count($nameExploded) > 3 && count($nameExploded) < 5){
           if(!empty($factory)){
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val4)')    
            ->andWhere('LOWER(p.name) LIKE LOWER(:val5)') 
            ->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)')    
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$nameExploded[0].'%')
            ->setParameter('val3', '%'.$nameExploded[1].'%')
            ->setParameter('val4', '%'.$nameExploded[2].'%')
            ->setParameter('val5', '%'.$nameExploded[3].'%')
            ->setParameter('val1', '%'.$factory.'%')   
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       }else{
           return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val4)') 
            ->andWhere('LOWER(p.name) LIKE LOWER(:val5)')
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$nameExploded[0].'%')
            ->setParameter('val3', '%'.$nameExploded[1].'%') 
            ->setParameter('val4', '%'.$nameExploded[2].'%')
            ->setParameter('val5', '%'.$nameExploded[3].'%')
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       } 
        }
        if(!empty($factory)){
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)')    
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$name.'%')
            ->setParameter('val1', '%'.$factory.'%')   
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       }else{
           return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$name.'%')  
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       } 
       
    }

    /**
      * @return Product[] Returns an array of Product objects
      */
    public function findAllPriceGreaterThanZero()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.catalog_price > 0')
            ->andWhere('p.is_on_promotion > 0')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
      * @return Product[] Returns an array of Product objects
      */
    public function findByFilters($name)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->setParameter('val', '%'.$value.'%')
            ->andWhere('p.catalog_price > 0')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    public function findOneBySomeName($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
