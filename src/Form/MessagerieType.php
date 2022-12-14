<?php

namespace App\Form;

use App\Entity\Messagerie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessagerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destinataire', EntityType::class, [
                "class" => User::class,
                "choice_label" => "email",
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('objet', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Entrez l\'objet de votre message',
                ]
            ])
            ->add('message', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Votre message',
                ]
            ])
            ->add('envoyer', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-primary"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messagerie::class,
        ]);
    }
}
