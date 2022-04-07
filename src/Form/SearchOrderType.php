<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Kontrahent;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchOrderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('number', TextType::class, [
                    'label' => 'Numer',
                    'required' => false,
                ])
                ->add('kontrahent', EntityType::class, [
                    'class' => Kontrahent::class,
                    'multiple' => false,
                    'expanded' => false,
                    'attr' =>['class' => 'chosen-select form-control'],
                    'label' => 'Kontrahent',
                    'placeholder' => 'Wybierz kontrahenta',
                    'required' => false
                ])
                ->add('status', ChoiceType::class, [
                    'choices' => [
                        "Wszystkie" =>"all" ,
                        "Aktywne"=> "active" ,
                        "Zamówione"=> "order" ,
                        "Zamknięte"=> "closed" ,
                        "Anulowane"=> "closed" 
                    ],
                    'label' => 'Status',
                    'required' => false
                ])
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'multiple' => true,
                    'expanded' => false,
                    'label' => 'Zadanie dla',
                    'required' => false
                ])
                ->add('filter', SubmitType::class, ['label' => 'Filtruj'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

}
