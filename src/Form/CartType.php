<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Transport;
use App\Entity\Kontrahent;
use App\Form\EventListener\ClearCartListener;
use App\Form\EventListener\RemoveItemListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CartType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('is_ordered', CheckboxType::class, [
                    'label' => 'Zamówienie wysłane',
                    'required' => false,
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Oferta' => 'offer',
                        'Zamówienie' => 'order',
                    ],
                    'label' => 'Typ'
                ])
                ->add('relation', EntityType::class, [
                    'class' => Transport::class,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Możliwy wjazd auta',
                ])
                ->add('delivery_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Data dostawy',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
                ->add('adress', TextareaType::class, [
                    'label' => 'Adres dostawy',
                    'required' => false,
                ])
                ->add('notice', TextareaType::class, [
                    'label' => 'Uwagi',
                    'required' => false,
                ])
                ->add('phone', TextType::class, [
                    'label' => 'Telefon',
                    'required' => false,
                ])
                ->add('kontrahent_group', ChoiceType::class, [
                    'choices' => [
                        'Detaliczna' => 'detal',
                        'Wykonawcy' => 'hurt',
                        'Hurtowa' => 'specjal',
                    ],
                    'label' => 'Grupa cenowa'
                ])
                ->add('pickup', ChoiceType::class, [
                    'choices' => [
                        'Plac Wieliczka' => 'pitch',
                        'Polbruk' => 'factory_po',
                        'Libet' => 'factory_li',
                        'Joniec' => 'factory_jo',
                        'Kost-Bet' => 'factory_ko',
                        'Chyż-bet' => 'factory_ch',
                        'Brukbet' => 'factory_br',
                    ],
                    'label' => 'Miejsce załadunku'
                ])
                ->add('is_pickup_wieliczka', CheckboxType::class, [
                    'label' => 'Doładunek Wieliczka',
                    'required' => false,
                ])
                ->add('count_pallets', CheckboxType::class, [
                    'label' => 'Dolicz Palety',
                    'required' => false,
                ])
                ->add('is_extra_delivery', CheckboxType::class, [
                    'label' => 'Przeładunek u klienta',
                    'required' => false,
                ])
                ->add('own_pickup', CheckboxType::class, [
                    'label' => 'Odbiór własny',
                    'required' => false,
                ])
                ->add('kontrahent', EntityType::class, [
                    'class' => Kontrahent::class,
                    'multiple' => false,
                    'expanded' => false,
                    'attr' =>['class' => 'chosen-select'],
                    'label' => 'Kontrahent',
                ])
                ->add('item', CollectionType::class, [
                    'entry_type' => CartItemType::class,
                    'allow_delete' => true,
                ])
                ->add('count', SubmitType::class, ['label' => 'Przelicz i Zapisz Dane'])
                ->add('save', SubmitType::class, ['label' => 'Zapisz produkty'])
                ->add('clear', SubmitType::class, ['label' => 'Czyść']);

        $builder->addEventSubscriber(new RemoveItemListener());
        $builder->addEventSubscriber(new ClearCartListener());
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

}
