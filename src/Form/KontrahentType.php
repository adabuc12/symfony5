<?php

namespace App\Form;

use App\Entity\Kontrahent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class KontrahentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Nazwa',
                    'attr'=>['class' => 'form-control'],
                ])
            ->add('Adress', TextType::class, [
                    'label' => 'Numer',
                    'attr'=>['class' => 'form-control'],
                'required' => false,
                ])
            ->add('phone', TextType::class, [
                    'label' => 'Telefon',
                'required' => false,
                ])
            ->add('email', TextType::class, [
                    'label' => 'Email',
                'required' => false,
                ])
            ->add('nip', TextType::class, [
                    'label' => 'NIP',
                    'attr'=>['class' => 'form-control'],
                'required' => false,
                ])
            ->add('notices', TextType::class, [
                    'label' => 'Uwagi',
                'required' => false,
                ])
            ->add('post_code', TextType::class, [
                    'label' => 'Kod Pocztowy',
                'required' => false,
                ])
            ->add('street', TextType::class, [
                    'label' => 'Ulica',
                'required' => false,
                ])
            ->add('class_name', ChoiceType::class, [
                    'choices' => [
                        'Detaliczna' => 'Detaliczna',
                        'Hurtowa' => 'Hurtowa',
                        'Specjalna' => 'Specjalna',
                    ],
                    'label' => 'Klasa'
                ])
            ->add('group_name', ChoiceType::class, [
                    'choices' => [
                        'Detaliczna' => 'Detaliczna',
                        'Hurtowa' => 'Hurtowa',
                        'Specjalna' => 'Specjalna',
                    ],
                    'label' => 'Grupa'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kontrahent::class,
        ]);
    }
}
