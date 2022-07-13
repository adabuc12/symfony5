<?php

namespace App\Storage;

use App\Entity\FactoryOrder;
use App\Repository\FactoryOrderRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class FactoryOrderSessionStorage
 * @package App\Storage
 */
class FactoryOrderSessionStorage
{
    /**
     * The session storage.
     *
     * @var SessionInterface
     */
    private $session;

    /**
     * The cart repository.
     *
     * @var OrderRepository
     */
    private $factoryOrderRepository;

    /**
     * @var string
     */
    const FACTORYORDER_KEY_NAME = 'factoryOrder_id';

    /**
     * FactoryOrderSessionStorage constructor.
     *
     * @param SessionInterface $session
     * @param FactoryOrderRepository $factoryOrderRepository
     */
    public function __construct(SessionInterface $session, FactoryOrderRepository $factoryOrderRepository) 
    {
        $this->session = $session;
        $this->factoryOrderRepository = $factoryOrderRepository;
    }

    /**
     * Gets the cart in session.
     *
     * @return FactoryOrder|null
     */
    public function getFactoryOrder(): ?FactoryOrder
    {
        return $this->factoryOrderRepository->findOneBy([
            'id' => $this->getFactoryOrderId()
        ]);
    }

    /**
     * Sets the cart in session.
     *
     * @param FactoryOrder $factoryOrder
     */
    public function setFactoryOrder(FactoryOrder $factoryOrder): void
    {
        $this->session->set(self::FACTORYORDER_KEY_NAME, $factoryOrder->getId());
    }

    /**
     * Returns the cart id.
     *
     * @return int|null
     */
    private function getFactoryOrderId(): ?int
    {
        return $this->session->get(self::FACTORYORDER_KEY_NAME);
    }
}