<?php

namespace App\Form;

use App\Entity\ProductCategory;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductCategoryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Nazwa kategori',
                ])
                ->add('product', EntityType::class, [
                    'class' => Product::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                                ->orderBy('p.name', 'ASC');
                    },
                    'choice_label' => 'name',
                     'multiple' => true,
                     'expanded' => false,
                    'group_by' => 'Manufacture',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => ProductCategory::class,
        ]);
    }

}
