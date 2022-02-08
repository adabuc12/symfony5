<?php

namespace App\Form;

use App\Entity\Payments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PaymentsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('kontrahent')
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Faktura zakupu' => 'faktura_zakup',
                        'Korekta zakupu' => 'korekta_zakup',
                        'Wpłata' => 'wplata',
                        'Wypłata' => 'wyplata'
                    ],
                    'label' => 'Typ'
                ])
                ->add('amount', MoneyType::class, [
                    'divisor' => 100,
                    'label' => 'Kwota',
                    'currency' => 'PLN'
                ])
                ->add('number', TextType::class, [
                    'label' => 'Numer',
                ])
                ->add('payment_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data płatności',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
                ->add('is_paid', CheckboxType::class, [
                    'label' => 'Zapłacone?',
                    'required' => false,
                ])
                ->add('is_printed', CheckboxType::class, [
                    'label' => 'Wydrukowane?',
                    'required' => false,
                ])
                ->add('notices', TextareaType::class, [
                    'label' => 'Uwagi',
                    'required' => false,
                ])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Payments::class,
        ]);
    }

}
