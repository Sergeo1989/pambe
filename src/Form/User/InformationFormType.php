<?php

namespace App\Form\User;

use App\Entity\User;
use App\Form\ProfessionalImageFormType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profile', ProfessionalImageFormType::class)
            ->add('email', EmailType::class, [
                'required' => false,
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
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne doit pas être vide.',
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne doit pas être vide.',
                    ])
                ]
            ])
            ->add('save', SubmitType::class); 
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity(['fields' => ['email'], 'message' => 'Cette adresse e-mail existe déjà.']),
            ]
        ]);
    }
}
