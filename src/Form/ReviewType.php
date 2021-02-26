<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom *',
                'constraints' => new NotBlank(),
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                // Sera écrasé si on le renseigne aussi dans le Twig
                'help' => 'Ceci est un message d\'aide'
                
            ])

            ->add('message', TextareaType::class, [
                'label' => 'Message *',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                    ])
                ]
            ])

            ->add('Email', EmailType::class, [
                'label' => 'Email *',
                'constraints' => [
                    // Non vide
                    new NotBlank(),
                    // Format E-mail valide
                    new Email(),
                ]
            ])

            ->add('Age', IntegerType::class, [
                'label' => 'Age *',
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
                'label' => 'Mot de passe *',
                'help' => 'Entre 8 et 16 caractères, une majuscule, une minuscule, un chiffre, $@%*+-_!',
                'constraints' => [
                    //new NotBlank(),
                    // - de 8 à 16 caractères
                    // - au moins une lettre minuscule
                    // - au moins une lettre majuscule
                    // - au moins un chiffre
                    // - au moins un de ces caractères spéciaux: $ @ % * + - _ !
                    new Regex('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/'),
                    new Length([
                        'min' => 8,
                        'max' => 16,
                    ])
                ]
            ])

            ->add('URL', UrlType::class, [
                'label' => 'Site Web',
                // @see https://symfony.com/doc/current/reference/forms/types/url.html#default-protocol
                // Protocole à ajouter à la saisie si protocole non précisé
                'default_protocol' => 'https',
                'constraints' => [
                    // Si protocole saisi par l'utilisateur,
                    // Quels sont les protocols autorisés. 
                    // @see https://symfony.com/doc/current/reference/constraints/Url.html#protocols
                    new Url([
                        'protocols' => ['http', 'https'],
                    ]),
                    new NotBlank(),
                ],
            ])

            ->add('rating', ChoiceType::class, [
                'label' => 'Avis',
                'placeholder' => 'Vous avez trouvé ce film...',
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => [
                    // Clé = libellé, Valeur = value de l'option
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1,

                ],

            ])

            ->add('feel',ChoiceType::class,[
                'label' => 'Ce film vous a fait : ',
                'constraints' => [
                    new NotBlank(),
                ],
                // Choix multiple renvoie un tableau
                'multiple'=>true,
                'expanded'=>true,
                'choices' => [
                    'Rire' => 0,
                    'Pleurer' => 1,
                    'Réfléchir' => 2,
                    'Dormir' => 3,
                    'Rêver' => 4
                ],
            ])

            ->add('date', DateType::class, [
                'widget' => 'choice',
                'label' => 'Vous avez vu ce film le',
                'years' => range(date('Y'), date('Y') - 50),
                

            ])

            ->add('cinema', FileType::class, [
                'label' => 'Photo du cinéma ou de la salle',
                'constraints' => [
                        new File([
                            'maxSize' => '4096k',
                            'mimeTypes' => [
                                'image/png',
                                'image/jpeg',
                            ],
                            'mimeTypesMessage' => 'Le fichier n\'est pas au bon format (formats acceptés: .png, .jpg, .jpeg)',
                        ]),
                    ]
                ])
            ;

            


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            
        ]);
    }
}
