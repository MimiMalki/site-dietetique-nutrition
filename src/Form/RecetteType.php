<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Allergie;
use App\Entity\Regime;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0',
                ],
            ])
            ->add('description',  TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0',
                    'cols' => '30',
                    'rows' => '3'
                ],
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
            ])
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
            ->add('accessiblePatient', CheckboxType::class, [
                'label' => 'Accessible pour les patients',
                'required' => false,
                'row_attr' => [
                    'class' => 'custom-switch',
                ],
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label_attr' => [
                    'class' => 'custom-control-label',
                ],
            ])
            // ->add('accessiblePatient',CheckboxType::class, [
            //     'required' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
    // public function finishView(FormView $view, FormInterface $form, array $options): void
    // {
    //     /** @var Recette|null $recette */
    //     $recette = $form->getData();

    //     if ($recette) {
    //         $tempsPreparation = $recette->getTempsPrepation();
    //         $tempsRepos = $recette->getTempsRepos();
    //         $tempsCuisson = $recette->getTempsCuisson();
    //         $tempsTotal = $tempsPreparation + $tempsRepos + $tempsCuisson;

    //         $view->vars['tempsTotal'] = $tempsTotal;
    //     }
    // }
}
