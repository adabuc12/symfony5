<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
            ])
            ->add('date_to_end', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Czas do realizacji',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Select a value',
                    'input' => 'datetime'
                ])
            ->add('priorytet', ChoiceType::class, [
                    'choices' => [
                        'Normalny' => '2',
                        'Wysoki' => '3',
                        'Niski' => '1',
                        'Pilny' => '4',
                    ],
                    'label' => 'Priorytet'
                ])
            ->add('user', EntityType::class, [
                    'class' => User::class,
                    'multiple' => true,
                    'expanded' => false,
                    'label' => 'Zadanie dla',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
