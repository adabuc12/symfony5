<?php

namespace App\Form;

use App\Entity\Kontrahent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KontrahentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('Adress')
            ->add('phone')
            ->add('email')
            ->add('nip')
            ->add('notices')
            ->add('post_code')
            ->add('street')
            ->add('class_name')
            ->add('group_name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kontrahent::class,
        ]);
    }
}
