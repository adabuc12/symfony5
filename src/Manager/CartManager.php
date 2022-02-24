<?php

namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CartManager
 * @package App\Manager
 */
class CartManager {
    /* @var Doctrine\ORM\EntityManager $em */

    protected $em;

    /**
     * @var Security
     */
    private $security;

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
    CartSessionStorage $cartStorage, OrderFactory $orderFactory, EntityManagerInterface $em, Security $security
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Gets the current cart.
     * 
     * @return Order
     */
    public function getCurrentCart(): Order {
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
    public function save(Order $cart): void {

        $dateImmutable = new \DateTime();
        if (empty($cart->getNumber())) {
            $lastOrder = $this->em->getRepository('App:Order')->findOneBy(['status'=>'offer'], ['id' => 'desc']);
            if (empty($lastOrder)) {
                $cart->setIsOrdered(false);
                $cart->setDeliveryDate($dateImmutable);
                $number = 1;
                $cart->setNumber($number . '/' . date("Y"));
            }

            if (!empty($lastOrder) && $cart->getNumber() !== $lastOrder->getNumber()) {
                $cart->setIsOrdered(false);
                $cart->setDeliveryDate($dateImmutable);

                if (!empty($lastOrder)) {
                    $lastNumber = $lastOrder->getNumber();
                    $number = explode('/', $lastNumber);
                    $number = $number[0];
                    $number = $number + 1;
                }
                $cart->setNumber($number . '/' . date("Y"));
            }
        }

        
        $cart_user = $cart->getUser();
        if (empty($cart_user)) {
            $user = $this->security->getUser();
            $cart->setUser($user);
        }

        if ($cart->getCreatedAt() == NULL) {
            $cart->setCreatedAt($dateImmutable);
        }


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
    public function clear(Order $cart) {
        // Persist in session
        $cart->removeItems();
    }
    
    public function setCart(Order $cart) {
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }

}
