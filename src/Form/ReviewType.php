<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Date;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => new NotBlank(),
            ])

            ->add('Prenom', TextType::class, [
                'label' => 'Prénom',
                
            ])

            ->add('textera', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 100,
                    ])
                ]
            ])

            ->add('Email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    // Non vide
                    new NotBlank(),
                    // Format E-mail valide
                    new Email(),
                ]
            ])

            ->add('Age', IntegerType::class, [
                'label' => 'Age',
                'constraints' => [
                    // Non vide
                    new NotBlank(),
                    new Range([
                        'min' => 7,
                        'max' => 77,
                    ])
                ]
                
            ])

            ->add('password', PasswordType::class, [
                // Supprimer le required HTML5
                'required' => false,
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 8,
                        'max' => 16,
                    ])
                ]
            ])

            ->add('URL', UrlType::class, [
                'label' => 'Site Web',
                'constraints' => [
                    new Url(),
                    new NotBlank(),
    
                ]
            ])

            ->add('Choice', ChoiceType::class, [
                'label' => 'Avis',
                'choices' => [
                    'Excellent' => 'Excellent',
                    'Très bon' => 'Très bon',
                    'Bon' => 'Bon',
                    'Peut mieux faire' => 'Peut mieux faire',
                    'A éviter' => 'A éviter',

                ],

            ])

            ->add('Checkboxes',ChoiceType::class,[
                'label' => 'Ce film vous a fait : ',
                'multiple'=>true,
                'expanded'=>true,
                'choices'=>[
                    'Rire'=>'Rire',
                    'Pleurer'=>'Pleurer',
                    'Réfléchir' => 'Réfléchir',
                    'Dormir' => 'Dormir',
                    'Rêver' => 'Rêver',


                ]
            ])

            ->add('Date', DateType::class, [
                'label' => 'Vous avez vu ce film le : ',
                

            ])

            ->add('File', FileType::class, [
                'label' => 'Photo du cinéma ou de la salle : '

            ])

            ->add('submit', SubmitType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
