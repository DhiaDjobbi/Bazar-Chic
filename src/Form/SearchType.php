<?php

namespace App\Form;

use App\Entity\Search;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category',EntityType::class,[
                'placeholder' => 'Search by Category',
                'class'=>Category::class,
                'choice_label'=>'name',
                'choice_value'  => 'name',
                'mapped' => true])

            ->add('minPrice')
            ->add('maxPrice')
            ->add('name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
