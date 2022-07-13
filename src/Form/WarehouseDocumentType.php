<?php

namespace App\Form;

use App\Entity\WarehouseDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Kontrahent;
use App\Entity\OrderItem;
use App\Form\WarehouseStockType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class WarehouseDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                    'choices' => [
                        'WZ' => 'wz',
                        'PZ' => 'pz',
                    ],
                    'label' => 'Typ dokumentu'
                ])
            ->add('date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data wystawienia',
                    'html5' => false,
                    'attr' => ['value' => date('Y-m-d'),'class' => 'datepicker'],
                    'placeholder' => 'Wybierz datę',
                    'input' => 'datetime'
                ])
            ->add('is_brutto', ChoiceType::class, [
                    'choices' => [
                        'NETTO' => false,
                        'BRUTTO' => true,
                    ],
                    'label' => 'Tryb sumowania'
                ])
            ->add('kontrahent', EntityType::class, [
                    'class' => Kontrahent::class,
                    'multiple' => false,
                    'expanded' => false,
                    'attr' =>['class' => 'chosen-select'],
                    'label' => 'Kontrahent',
                    'placeholder' => 'Wybierz kontrahenta'
                ])
            ->add('product_item', CollectionType::class, [
                'entry_type' => WarehouseStockType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WarehouseDocument::class,
        ]);
    }
}
