<?php

namespace App\Form;

use App\Entity\FactoryOrder;
use App\Entity\Driver;
use App\Form\OrderFactoryItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FactoryOrderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                 ->add('driver', EntityType::class, [
                    'class' => Driver::class,
                    'multiple' => true,
                    'expanded' => false,
                    'label' => 'Kierowca',
                ])
                ->add('delivery_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data odbioru',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
                ->add('orderFactoryItems', CollectionType::class, [
                    'entry_type' => OrderFactoryItemType::class,
                    'allow_delete' => true,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => FactoryOrder::class,
        ]);
    }

}
