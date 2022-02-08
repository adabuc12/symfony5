<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * Class AbstractSubscriber
 * @package App\EventSubscriber
*/
abstract class AbstractSubscriber
{
    /**
     * Default action for logs
     */
    const UNKNOWN_ACTION = 'unknown_action';
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * AbstractSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param string $action
     * @param array $entityFields
     */
    protected function logEntity($action = self::UNKNOWN_ACTION, array $entityFields)
    {
        $this->container->get('monolog.logger.db')->info($action, [
            'entity' => $entityFields
        ]);
    }
}
