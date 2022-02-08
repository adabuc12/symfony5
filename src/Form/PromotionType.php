<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\ProductCategory;;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
            ])
            ->add('price_types', ChoiceType::class, [
                    'choices' => [
                        'detaliczna' => 'detal',
                        'hurtowa' => 'hurt',
                        'specjalna' => 'specjal',

                    ],
                    'expanded' => 'flase',
                    'multiple' => 'true',
                    'label' => 'Cena (typ kontrahenta) produktu na promocji'
                ])
            ->add('cart_condition_type', ChoiceType::class, [
                    'choices' => [
                        '' => null,
                        'm2' => 'm2',
                        'szt' => 'szt',
                        'wartość' => 'value',
                        'zysk' => 'earnings',

                    ],
                    'label' => 'Typ warunku oferty/zamówienia'
                ])
            ->add('cart_condition', ChoiceType::class, [
                    'choices' => [
                        '' => null,
                        'większe' => '>',
                        'mniejsze' => '<',
                        'równe' => '=',

                    ],
                    'label' => 'Warunek oferty/zamówienia'
                ])
            ->add('cart_condition_value', TextType::class, [
                'label' => 'Wartość warunku oferty/zamówienia',
                'required'   => false,
            ])
            ->add('product_condition_type', ChoiceType::class, [
                    'choices' => [
                        '' => null,
                        'wartość suma' => 'value',
                        'ilość' => 'quantity',
                        'zysk suma' => 'earnings',

                    ],
                    'label' => 'Typ warunku produktu'
                ])
            ->add('product_condition', ChoiceType::class, [
                    'choices' => [
                        '' => null,
                        'większe' => '>',
                        'mniejsze' => '<',
                        'równe' => '=',

                    ],
                    'label' => 'Warunek produktu'
                ])
            ->add('product_condition_value', TextType::class, [
                'label' => 'Wartość warunku produktu',
                'required'   => false,
            ])
            ->add('price_condition_type', ChoiceType::class, [
                    'choices' => [
                        '' => null,
                        'cena jednostkowa' => 'price',
                        'zysk jednostkowy' => 'earnings',

                    ],
                    'label' => 'Typ warunku ceny'
                ])
            ->add('price_condition', ChoiceType::class, [
                    'choices' => [
                        '' => 'null',
                        'większe' => '>',
                        'mniejsze' => '<',
                        'równe' => '=',

                    ],
                    'label' => 'Warunek ceny produktu'
                ])
            ->add('price_condition_value', TextType::class, [
                'label' => 'Wartość warunku ceny',
                'required'   => false,
            ])
                ->add('calculation_type', ChoiceType::class, [
                    'choices' => [
                        'wartość całościowa' => 'cart_value',
                        'wartość produktu' => 'product_value',
                        'cena produktu' => 'product_price',
                        'produkt' => 'product',

                    ],
                    'label' => 'Typ wyniku promocji'
                ])
                ->add('calculation_count_type', ChoiceType::class, [
                    'choices' => [
                        'dodaj' => '+',
                        'odejmij' => '-',
                        'ustaw równą' => '=',

                    ],
                    'label' => 'Co zrobić z wynikiem'
                ])
                ->add('calculation_count_value',  TextType::class, [
                'label' => 'Wartość warunku promocji',
                'required'   => false,
            ])
                ->add('calculation_count_is_percent')
            ->add('is_enabled')
            ->add('start_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data start',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
            ->add('end_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data koniec',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
            ->add('product_category', EntityType::class, [
                    'class' => ProductCategory::class,
                    'multiple' => true,
                    'expanded' => false,
                    'label' => 'Zadanie dla',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
