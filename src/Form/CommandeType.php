<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Transporteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('adresse', EntityType::class, [
                'label' => FALSE,
                'required' => true,
                'class' => Adresse::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('transporteur', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class' => Transporteur::class,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn p-2 btn-success' 
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
