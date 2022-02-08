<?php

namespace App\Form;

use App\Entity\FactoryOrder;
use App\Form\OrderFactoryItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FactoryOrderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
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
