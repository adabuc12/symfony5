<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProductAvailibilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estimated_availability_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Planowany czas dostępności/kolejnego zapytania o dostępność',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
