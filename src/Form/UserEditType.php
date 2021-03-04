<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserEditType extends AbstractType
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
            ->add('password', PasswordType::class, [
                // Si données vides (null), remplacer par chaîne vide
                // @see https://symfony.com/doc/current/reference/forms/types/password.html#empty-data
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Laissez vide si inchangé',
                ],
                // @see https://symfony.com/doc/current/reference/forms/types/email.html#mapped
                // Ce champ ne sera présent que dans la requête et dans le form
                // mais PAS dans l'Entité !
                'mapped' => false,
            

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}