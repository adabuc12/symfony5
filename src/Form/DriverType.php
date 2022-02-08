<?php

namespace App\Form;

use App\Entity\Driver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('phone')
            ->add('registration_number')
            ->add('car_wight')
            ->add('car_long')
            ->add('car_height')
            ->add('is_hds')
            ->add('axis')
            ->add('package_max_weight')
            ->add('max_pallets')
            ->add('prices')
            ->add('deliveries')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Driver::class,
        ]);
    }
}
