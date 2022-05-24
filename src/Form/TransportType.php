<?php

namespace App\Form;

use App\Entity\Transport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('weight')
            ->add('pallet_places')
            ->add('price_5')
            ->add('price_10')
            ->add('price_15')
            ->add('price_20')
            ->add('price_25')
            ->add('price_30')
            ->add('price_35')
            ->add('price_40')
            ->add('price_45')
            ->add('price_50')
            ->add('price_55')
            ->add('price_60')
            ->add('price_65')
            ->add('price_70')
            ->add('price_75')
            ->add('price_80')
            ->add('price_85')
            ->add('price_90')
            ->add('price_95')
            ->add('price_100')
            ->add('driver_name')
            ->add('registration_number')
            ->add('phone')
            ->add('notices')
                ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }
}
