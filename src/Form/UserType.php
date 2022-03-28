<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('email', TextType::class, [
                    'label' => 'Email',
                    'required' => false,
                ])
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'SUPERADMIN' => 'ROLE_SUPERADMIN',
                        'ADMIN' => 'ROLE_ADMIN',
                        'USER' => 'ROLE_USER',
                    ],
                    'label' => 'Rola',
                    'multiple' => true,
                    'mapped' => true,
                    'expanded' => false,
                ])
                ->add('password', TextType::class, [
                    'label' => 'Hasło',
                    'required' => false,
                ])
                ->add('isVerified', CheckboxType::class, [
                    'label' => 'Zweryfikowany',
                    'required' => false,
                ])
                ->add('name', TextType::class, [
                    'label' => 'Imię',
                    'required' => false,
                ])
                ->add('surname', TextType::class, [
                    'label' => 'Nazwisko',
                    'required' => false,
                ])
                ->add('phone', TextType::class, [
                    'label' => 'Telefon',
                    'required' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
