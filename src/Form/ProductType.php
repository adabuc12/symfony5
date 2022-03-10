<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Factory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Nazwa',
                    'required' => true,
                ])
            ->add('Manufacture', TextType::class, [
                    'label' => 'Fabryka',
                    'required' => true,
                ])
            ->add('is_on_promotion', CheckboxType::class, [
                    'label' => 'Na promocji',
                    'required' => false,
                ])
            ->add('packaging', TextType::class, [
                    'label' => 'Pakowanie',
                    'required' => true,
                ])
            ->add('package_weight', TextType::class, [
                    'label' => 'Waga opakowania',
                    'required' => true,
                ])
            ->add('unit_weight', TextType::class, [
                    'label' => 'Waga /m2/szt',
                    'required' => true,
                ])
            ->add('catalog_price', TextType::class, [
                    'label' => 'Cena katalogowa',
                    'required' => true,
                ])
            ->add('buy_price', TextType::class, [
                    'label' => 'Cena zakupu',
                    'required' => true,
                ])
            ->add('sell_price_factory_detal', TextType::class, [
                    'label' => 'Cena sprzedaży - DETAL - FABRYKA',
                    'required' => true,
                ])
            ->add('sell_price_pitch_detal', TextType::class, [
                    'label' => 'Cena sprzedaży - DETAL - PLAC',
                    'required' => true,
                ])
            ->add('sell_price_factory_contractors', TextType::class, [
                    'label' => 'Cena sprzedaży - WYKONAWCY - FABRYKA',
                    'required' => true,
                ])
            ->add('sell_price_pitch_contractors', TextType::class, [
                    'label' => 'Cena sprzedaży - WYKONAWCY - PLAC',
                    'required' => true,
                ])
            ->add('sell_price_factory_wholesale', TextType::class, [
                    'label' => 'Cena sprzedaży - HURT - FABRYKA',
                    'required' => true,
                ])
            ->add('sell_price_pitch_wholesale', TextType::class, [
                    'label' => 'Cena sprzedaży - HURT - PLAC',
                    'required' => true,
                ])
            ->add('is_courier', CheckboxType::class, [
                    'label' => 'Wysyłka kurierem',
                    'required' => false,
                ])
            ->add('courier_cost', TextType::class, [
                    'label' => 'Cena kuriera',
                    'required' => false,
                ])
            ->add('is_not_available', CheckboxType::class, [
                    'label' => 'Produkt niedostępny',
                    'required' => false,
                ])
            ->add('estimated_availability_date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Przewidywany czas dostępności',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'placeholder' => 'Wybierz',
                    'input' => 'datetime'
                ])
            ->add('notices', TextareaType::class, [
                    'label' => 'Uwagi',
                    'required' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
