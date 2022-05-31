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
        
        $query = $this->createQueryBuilder('p')->andWhere('p.catalog_price > 0');

        
        if (count($nameExploded) == 1){
                $query->andWhere('LOWER(p.name) LIKE LOWER(:val)')
                ->setParameter('val', '%'.$nameExploded[0].'%');
            }
            
            if (count($nameExploded) > 1 && count($nameExploded) < 3){
                $query->andWhere('LOWER(p.name) LIKE LOWER(:val)')
                ->andWhere('LOWER(p.name) LIKE LOWER(:val3)')
                ->setParameter('val', '%'.$nameExploded[0].'%')
                ->setParameter('val3', '%'.$nameExploded[1].'%');
            }
             
            if (count($nameExploded) > 2 && count($nameExploded) < 4){
                $query->andWhere('LOWER(p.name) LIKE LOWER(:val4)')
                ->setParameter('val4', '%'.$nameExploded[2].'%');
            }
            if (count($nameExploded) > 3 && count($nameExploded) < 5){
                $query->andWhere('LOWER(p.name) LIKE LOWER(:val4)')
                ->andWhere('LOWER(p.name) LIKE LOWER(:val5)') 
                ->setParameter('val4', '%'.$nameExploded[2].'%')
                ->setParameter('val5', '%'.$nameExploded[3].'%');
            }

           if(!empty($factory)){
                $query->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)')    
                ->setParameter('val1', '%'.$factory.'%');
            }
        
        return $query->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    
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
    public function findAllPriceGreaterThanZeroNotOnPromotion($name,$factory)
    {
        if(!empty($name) || !empty($factory)){
            
            return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)')    
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$name.'%')
            ->setParameter('val1', '%'.$factory.'%')   
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
        ;
        }
        return $this->createQueryBuilder('p')
            ->andWhere('p.catalog_price > 0')
            ->addOrderBy('p.Manufacture', 'ASC')
        ;
    }
        /**
      * @return Product[] Returns an array of Product objects
      */
    public function findAllPriceGreaterThanZeroNotOnPromotionCategory($name,$factory,$searchCategory)
    {
        if(!empty($name) || !empty($factory) || !empty($searchCategory)){
            

            return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) LIKE LOWER(:val)')
            ->andWhere('LOWER(p.Manufacture) LIKE LOWER(:val1)') 
            ->innerJoin('p.productCategories', "c")
            ->andWhere('LOWER(c.name) LIKE LOWER(:val2)') 
            ->andWhere('p.catalog_price > 0')
            ->setParameter('val', '%'.$name.'%')
            ->setParameter('val1', '%'.$factory.'%')
            ->setParameter('val2', '%'.$searchCategory.'%')
            ->addOrderBy('p.Manufacture', 'DESC')
            ->addOrderBy('p.name', 'ASC')
        ;
        }
        
        return $this->createQueryBuilder('p')
            ->andWhere('p.catalog_price > 0')
            ->addOrderBy('p.Manufacture', 'ASC')
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
    
    public function findOneById($id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
