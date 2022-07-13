<?php

namespace App\Storage;

use App\Entity\WarehouseDocument;
use App\Repository\WarehouseDocumentRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class FactoryOrderSessionStorage
 * @package App\Storage
 */
class StockSessionStorage
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
    private $warehouseDocumentRepository;

    /**
     * @var string
     */
    const WAREHOUSEDOCUMENT_KEY_NAME = 'warehouseDocument_id';

    /**
     * StockSessionStorage constructor.
     *
     * @param SessionInterface $session
     * @param WarehouseDocumentRepository $warehouseDocumentRepository
     */
    public function __construct(SessionInterface $session, WarehouseDocumentRepository $warehouseDocumentRepository) 
    {
        $this->session = $session;
        $this->warehouseDocumentRepository = $warehouseDocumentRepository;
    }

    /**
     * Gets the cart in session.
     *
     * @return FactoryOrder|null
     */
    public function getWarehouseDocument(): ?WarehouseDocument
    {
        return $this->warehouseDocumentRepository->findOneBy([
            'id' => $this->getWarehouseDocumentId()
        ]);
    }

    /**
     * Sets the cart in session.
     *
     * @param FactoryOrder $factoryOrder
     */
    public function setWarehouseDocument(WarehouseDocument $warehouseDocument): void
    {
        $this->session->set(self::WAREHOUSEDOCUMENT_KEY_NAME, $warehouseDocument->getId());
    }

    /**
     * Returns the cart id.
     *
     * @return int|null
     */
    private function getWarehouseDocumentId(): ?int
    {
        return $this->session->get(self::WAREHOUSEDOCUMENT_KEY_NAME);
    }
}

