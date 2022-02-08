<?php

namespace App\Form;

use App\Entity\Delivery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('delivery_order')
            ->add('driver')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }
}
