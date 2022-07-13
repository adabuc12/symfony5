<?php

namespace App\Factory;

use App\Entity\WarehouseDocument;
use App\Entity\WarehouseStock;
use App\Entity\Product;

/**
 * Class WarehouseDocumentFactory
 * @package App\Factory
 */
class WarehouseDocumentFactory
{
    /**
     * Creates an order.
     *
     * @return WarehouseDocument
     */
    public function create(): WarehouseDocument
    {
        $order = new WarehouseDocument();
        $order
            ->setDateCreated(new \DateTime());

        return $order;
    }

    /**
     * Creates an item for a product.
     *
     * @param Product $product
     *
     * @return WarehouseStock
     */
    public function createItem(Product $product): WarehouseStock
    {
        $item = new WarehouseStock();
        $item->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }
}