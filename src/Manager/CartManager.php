<?php

namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CartManager
 * @package App\Manager
 */
class CartManager
{
    /* @var Doctrine\ORM\EntityManager $em */
    protected $em;

    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory
     */
    private $cartFactory;

    /**
     * CartManager constructor.
     *
     * @param CartSessionStorage $cartStorage
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $em
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->em = $em;
    }

    /**
     * Gets the current cart.
     * 
     * @return Order
     */
    public function getCurrentCart(): Order
    {
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }


    /**
     * Persists the cart in database and session.
     *
     * @param Order $cart
     */
    public function save(Order $cart): void
    {   
        $lastOrder = $this->em->getRepository('App:Order')->findOneBy([], ['id' => 'desc']);
        $dateImmutable = new \DateTime();
        if($lastOrder !== null){
            $lastNumber = $lastOrder->getNumber();
            $number = explode('/', $lastNumber);
            $number = $number[0];
            $number+1;
        }else{
            $number = 1;
        }
        $cart->setNumber($number.'/'.date("Y"));
        $cart->setDate($dateImmutable);
        $cart->setIsOrdered(false);
        $cart->setDeliveryDate($dateImmutable);
        // Persist in database
        $this->em->persist($cart);
        $this->em->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }
    
    /**
     * delete the cart in database and session.
     *
     */
    public function clear(Order $cart)
    {   
        // Persist in session
        $cart->removeItems();
    }
}