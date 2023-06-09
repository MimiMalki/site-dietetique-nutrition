<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Allergie;
use App\Entity\Regime;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description',  TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0',
                    'cols' => '30',
                    'rows' => '3'
                ],
            ])
            ->add('imageFile', FileType::class)
            ->add('tempsPrepation')
            ->add('tempsRepos')
            ->add('tempsCuisson')
            ->add('ingredients',  TextareaType::class, [
                'label' => 'Ingredients',
                'attr' => [
                    'class' => 'peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0',
                    'cols' => '30',
                    'rows' => '3'
                ],
            ])
            ->add('etapes',  TextareaType::class, [
                'label' => 'Etapes',
                'attr' => [
                    'class' => 'peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0',
                    'cols' => '30',
                    'rows' => '3'
                ],
            ])
            ->add('allergies', EntityType::class, [
                'class' => Allergie::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('regimes', EntityType::class, [
                'class' => Regime::class,
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('accessiblePatient',CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
