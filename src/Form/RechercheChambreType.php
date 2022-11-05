<?php

namespace App\Form;

use App\Entity\Category;
use App\Services\RechercheChambre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_arrive', DateType::class, [
            'widget' => 'single_text',
            ])
            ->add('date_depart', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('category', EntityType::class ,[
                'class' => Category::class,
                'choice_label' =>'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'date_class' => RechercheChambre::class
        ]);
    }
}
