<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('Manufacture')
            ->add('is_on_promotion')
            ->add('packaging')
            ->add('package_weight')
            ->add('unit_weight')
            ->add('catalog_price')
            ->add('buy_price')
            ->add('sell_price_factory_detal')
            ->add('sell_price_pitch_detal')
            ->add('sell_price_factory_contractors')
            ->add('sell_price_pitch_contractors')
            ->add('sell_price_factory_wholesale')
            ->add('sell_price_pitch_wholesale')
            ->add('is_courier')
            ->add('courier_cost')
            ->add('is_not_available')
            ->add('estimated_availability_date')
            ->add('notices')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
