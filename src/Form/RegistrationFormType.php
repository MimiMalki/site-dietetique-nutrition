<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Allergie;
use App\Entity\Regime;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['placeholder' => 'Entrer votre nom'],
            'label' => 'Nom',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre nom',
                ])
            ],
        ])
        ->add('prenom', TextType::class, [
            'attr' => ['placeholder' => 'Entrer votre prénom'],
            'label' => 'Prénom',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre prénom',
                ])
            ],
        ])
        ->add('numTelephone', TextType::class, [
            'attr' => ['placeholder' => 'Entrer votre numéro de téléphone'],
            'label' => 'Numéro de téléphone',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre numéro de téléphone',
                ])
            ],
        ])
        ->add('email', EmailType::class, [
            'attr' => ['placeholder' => 'Entrer votre email'],
            'label' => 'Email',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre email',
                ]),
                new Email([
                    'message' => 'L\'adresse email "{{ value }}" n\'est pas valide',
                    // message affiché si l'utilisateur saisit une adresse email incorrecte
                ]),
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            // ->add('password', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
            //     'options' => ['attr' => ['class' => 'password-field']],
            //     'required' => true,
            //     'first_options' => ['label' => 'Mot de passe'],
            //     'second_options' => ['label' => 'Confirmer le mot de passe'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez entrer votre mot de passe',
            //         ]),
            //         new Length(
            //             [
            //                 'min' => 6, 'max' => 255,
            //                 'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
            //             ],
            //         ),
            //     ],
            // ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
