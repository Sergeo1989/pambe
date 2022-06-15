<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    private $translate;

    public function __construct(TranslatorInterface $translate)
    {
        $this->translate = $translate;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('email', EmailType::class, [
                    'constraints' => [
                        new Email([
                            'message' => 'L\'e-mail {{ value }} n\'est pas valide.',
                        ]),
                        new NotBlank([
                            'message' => 'L\'e-mail ne doit pas être vide.',
                        ])
                    ],
                ])
                ->add('firstname', TextType::class, [
                    'required' => true,
                    'label' => 'Prénom',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le prénom ne doit pas être vide.',
                        ])
                    ]
                ])
                ->add('lastname', TextType::class, [
                    'required' => true,
                    'label' => 'Nom',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le nom ne doit pas être vide.',
                        ])
                    ]
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'required' => true,
                    'first_options'  => ['label' => 'user.password'],
                    'second_options' => ['label' => 'user.passwordrepeat'],
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe ne doit pas être vide.',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                    ],
                ])
                ->add('address', TextType::class, [
                    'required' => true,
                    'label' => 'adresse',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'L\'adresse ne doit pas être vide.',
                        ])
                    ]
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez cocher ce champ.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity(['fields' => ['email'], 'message' => 'Cette adresse e-mail existe déjà.']),
            ]
        ]);
    }
}
