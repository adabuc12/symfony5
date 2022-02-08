<?php

namespace App\Factory;

use App\Entity\FactoryOrder;
use App\Entity\OrderFactoryItem;
use App\Entity\Product;

/**
 * Class FactoryOrderFactory
 * @package App\Factory
 */
class FactoryOrderFactory
{
    /**
     * Creates an order.
     *
     * @return FactoryOrder
     */
    public function create(): FactoryOrder
    {
        $order = new FactoryOrder();
        $order
            ->setDateCreated(new \DateTime());

        return $order;
    }

    /**
     * Creates an item for a product.
     *
     * @param Product $product
     *
     * @return OrderFactoryItem
     */
    public function createItem(Product $product): OrderFactoryItem
    {
        $item = new OrderFactoryItem();
        $item->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }
}