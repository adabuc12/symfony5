<?php

namespace App\EventSubscriber;

use App\Events\OrderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderSubscriber
 * @package AppBundle\EventSubscriber
 */
class OrderSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            OrderEvent::ORDER_ADDED    => 'onOrderAdded',
            OrderEvent::ORDER_UPDATED  => 'onOrderUpdated',
            OrderEvent::ORDER_DELETED  => 'onOrderDeleted'
        ];
    }

    /**
     * @param OrderEvent $event
     */
    public function onOrderAdded(OrderEvent $event)
    {
        $this->logEntity(OrderEvent::ORDER_ADDED, [
            'product' => $event->getEntity()->getProduct()
        ]);
    }

    /**
     * @param OrderEvent $event
     */
    public function onOrderUpdated(OrderEvent $event)
    {
        $this->logEntity(OrderEvent::ORDER_UPDATED, [
            'product' => $event->getEntity()->getProduct()
        ]);
    }

    /**
     * @param OrderEvent $event
     */
    public function onOrderDeleted(OrderEvent $event)
    {
        $this->logEntity(OrderEvent::ORDER_DELETED, [
            'product' => $event->getEntity()->getProduct()
        ]);
    }
}