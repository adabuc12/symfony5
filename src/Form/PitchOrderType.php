<?php

namespace App\Form;

use App\Entity\PitchOrder;
use App\Form\OrderFactoryItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PitchOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('relation', CollectionType::class, [
                    'entry_type' => OrderFactoryItemType::class,
                    'allow_delete' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PitchOrder::class,
        ]);
    }
}
