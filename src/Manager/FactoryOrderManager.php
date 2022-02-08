<?php

namespace App\Manager;

use App\Entity\FactoryOrder;
use App\Factory\FactoryOrderFactory;
use App\Storage\FactoryOrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CartManager
 * @package App\Manager
 */
class FactoryOrderManager {
    /* @var Doctrine\ORM\EntityManager $em */

    protected $em;

    /**
     * @var Security
     */
    private $security;
    
    /**
     * @var FactoryOrderSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var FactoryOrderFactory
     */
    private $factoryOrderFactory;

    /**
     * CartManager constructor.
     *
     * @param FactoryOrderSessionStorage $factoryOrderStorage
     * @param FactoryOrderFactory $factoryOrderFactory
     */
    public function __construct(
    FactoryOrderSessionStorage $factoryOrderStorage, FactoryOrderFactory $factoryOrderFactory, EntityManagerInterface $em, Security $security
    ) {
        $this->factoryOrderSessionStorage = $factoryOrderStorage;
        $this->factoryOrderFactory = $factoryOrderFactory;
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Gets the current cart.
     * 
     * @return FactoryOrder
     */
    public function getCurrentFactoryOrder(): FactoryOrder {
        $factoryOrder = $this->factoryOrderSessionStorage->getFactoryOrder();

        if (!$factoryOrder) {
            $factoryOrder = $this->factoryOrderFactory->create();
        }

        return $factoryOrder;
    }

    /**
     * Persists the cart in database and session.
     *
     * @param FactoryOrder $factoryOrder
     */
    public function save(FactoryOrder $factoryOrder): void {
        $lastOrder = $this->em->getRepository('App:FactoryOrder')->findOneBy([], ['id' => 'desc']);
        $dateImmutable = new \DateTime();
        $factoryOrder_user = $factoryOrder->getCreatedBy();
        $factoryOrder->setDateSended($dateImmutable);
        if(empty($factoryOrder_user)){
            $user = $this->security->getUser();
            $factoryOrder->setCreatedBy($user);
        }
        if(empty($lastOrder)){
            $number = 1;
            $factoryOrder->setNumber($number . '/' . date("Y"));
        }
        
        if (!empty($lastOrder) && $factoryOrder->getNumber() !== $lastOrder->getNumber() ) {
            
            if (!empty($lastOrder)) {
                $lastNumber = $lastOrder->getNumber();
                $number = explode('/', $lastNumber);
                $number = $number[0];
                $number = $number + 1;

            }
            $factoryOrder->setNumber($number . '/' . date("Y"));

        }
        if ($factoryOrder->getDateCreated() == NULL) {
            $factoryOrder->setDateCreated($dateImmutable);
        }


        // Persist in database
        $this->em->persist($factoryOrder);
        $this->em->flush();
        // Persist in session
        $this->factoryOrderSessionStorage->setFactoryOrder($factoryOrder);
    }

    /**
     * delete the cart in database and session.
     *
     */
    public function clear(FactoryOrder $factoryOrder) {
        // Persist in session
        $factoryOrder->removeItems();
    }

}
