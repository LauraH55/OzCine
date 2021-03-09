<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Manager' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // Tableau attendu côté PHP
                'multiple' => true,
                // Checkboxes
                'expanded' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // Le user mappé sur le form
                $user = $event->getData();
                // L'objet form à récupérer pour travailler avec
                // (car il est inconnu dans cette fonction anonyme)
                $form = $event->getForm();

                // L'entité $user existe-t-il en BDD ?
                // Si $user a un identifiant, c'est qu'il existe en base
                if ($user->getId() === null) {
                    // Si non => add
                    $form->add('password', PasswordType::class, [
                        // Si donnée vide (null), remplacer par chaine vide
                        // @see https://symfony.com/doc/current/reference/forms/types/password.html#empty-data
                        'empty_data' => '',
                        'constraints' => [
                            new NotBlank(),
                            new Length([
                                'min' => 4,
                            ])
                        ]
                    ]);
                } else {
                    // Si oui => edit
                    $form->add('password', PasswordType::class, [
                        // Si donnée vide (null), remplacer par chaine vide
                        // @see https://symfony.com/doc/current/reference/forms/types/password.html#empty-data
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé',
                        ],
                        // @see https://symfony.com/doc/current/reference/forms/types/email.html#mapped
                        // Ce champ ne sera présent que dans la requête et dans le form
                        // mais PAS dans l'entité !
                        'mapped' => false,
                        // 'constraints' => [
                        //     new Length([
                        //         'min' => 4,
                        //     ])
                        // ]
                    ]);
                }

            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
