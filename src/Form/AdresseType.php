<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => 'Ajoutez un nom à votre adresse :',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('prenom', TextType::class,[
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Entrer votre prénom'
                ]
            ])
            ->add('societe', TextType::class,[
                'label' => 'Votre société',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrer le nom de votre société'
                ]
            ])
            ->add('adresse', TextType::class,[
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => '8 rues des Lylas...'
                ]
            ])
            ->add('codePostal', TextType::class,[
                'label' => 'Votre code postal',
                'attr' => [
                    'placeholder' => 'Entrer votre code postal'
                ]
            ])
            ->add('ville', TextType::class,[
                'label' => 'Ville ',
                'attr' => [
                    'placeholder' => 'Entrer votre villle'
                ]
            ])
            ->add('pays', CountryType::class,[
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Votre pays'
                ]
            ])
            ->add('telephone', TelType::class,[
                'label' => 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Entrer votre téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                   'class' => 'btn-block btn-success mt-4 py-2d-flex w-50',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
