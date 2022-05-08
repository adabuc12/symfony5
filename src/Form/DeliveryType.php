<?php

namespace App\Form;

use App\Entity\Delivery;
use App\Entity\Driver;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;

class DeliveryType extends AbstractType {

    private $entityManager;
    
    private $orderId;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getOrderRepository(): OrderRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }
    
    private function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }
    
    private function getOrderId()
    {
        return $this->orderId ;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
         $this->setOrderId($options['orderId']);
        $builder
                ->add('delivery_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data dostawy',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Wybierz datę',
                    'input' => 'datetime'
                ])
                ->add('notices', TextareaType::class, [
                    'label' => 'Uwagi',
                    'required' => false,
                ])
                ->add('driver', EntityType::class, [
                    'class' => Driver::class,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Kierowca',
                ])
                ->add('delivery_order', EntityType::class, [
                    'class' => Order::class,
                    'placeholder' => '-- Wybierz --',
                    'query_builder' => function ($orderId) {
                        return $this->getOrderRepository()->createQueryBuilder('o')
                                ->where('o.id = :id')
                                ->orderBy('o.number', 'ASC')
                                ->setParameter('id', $this->getOrderId());
                    },
                    'choice_label' => 'number',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Zamówienie',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
            'orderId' => null,
        ]);
    }

}
