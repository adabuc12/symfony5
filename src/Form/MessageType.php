<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MessageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('text', TextareaType::class, [
                    'label' => 'Treść',
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Email' => 'Email',
                        'SMS' => 'SMS',
                        'Info' => 'Info',
                    ],
                    'label' => 'Typ'
                ])
                ->add('adress', TextType::class, [
                    'label' => 'Adres',
                    'required' => false,
                ])
                ->add('submit', SubmitType::class, ['label' => 'Wyślij'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }

}
