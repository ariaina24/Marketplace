<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class ,[
            'label'=>'Nom',
        'constraints' => new Length([
            'min'=>2,
            'max'=>30
        ]),
        'attr'=>['placeholder'=>'Saisir votre nom ici']])
        ->add('prenom',TextType::class ,[
            'label'=>'Prenom',
            'constraints' => new Length([
                'min'=>2,
                'max'=>30
            ]),
        'attr'=>['placeholder'=>'Saisir votre prenom ici']])

        ->add('email',EmailType::class ,[
            'label'=>'E-mail',
            'constraints' => new Length([
                'min'=>2,
                'max'=>30
            ]),
        'attr'=>['placeholder'=>'Saisir votre e-mail ici']])

        ->add('password', RepeatedType::class,[
            'type'=> PasswordType::class,
            'constraints' => new Length([
                'min'=>2,
                'max'=>30
            ]),
            'invalid_message'=>'Le mot de passe et la confirmation doivent être identique.',
            'label'=>'Mot de passe',
            'required'=>true,
            'first_options'=> [
                'label'=>'Mot de passe',
                'attr'=>['placeholder'=>'Saisir votre mot de passe']    
            
            ],
            'second_options'=>[
                'label'=>'Confirmation du mot de passe',
                'attr'=>['placeholder'=>'Confirmer votre mot de passe ']
                ]
        ])
        ->add('save',SubmitType::class,[
            'label'=>"S'inscrire",
            'attr'=>[
                'class'=>"btn btn-primary mt-3"
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
