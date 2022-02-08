<?php

namespace App\Events;

/**
 * Class OrderEvent
 * @package App\Events
 */
class OrderEvent extends AbstractEvent
{
    const ORDER_ADDED   = 'order_added';
    const ORDER_UPDATED = 'order_updated';
    const ORDER_DELETED = 'order_deleted';
}
