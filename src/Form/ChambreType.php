<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Chambre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tarif')
            ->add('titre')
            ->add('numero')
            ->add('description')
            ->add('image', FileType::class,
            [
                'data_class' => null,
                // pas obligé de charger une image, on peut par ex inscrire quelqu'un sans img (image par défault)
                'required' => false,
                'empty_data' => ""
            ])
            ->add('status')
            ->add('category', EntityType::class ,[
                'class' => Category::class,
                'choice_label' =>'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
