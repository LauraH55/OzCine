<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => new NotBlank(),
            ])
            ->add('releaseDate', DateType::class, [
                'widget' => 'choice',
                'label' => 'Ce film est sorti le',
                'years' => range(date('Y'), date('Y') - 70),
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'multiple'=>true,
                'expanded'=>true,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Movie::class,
        ]);
    }
}
