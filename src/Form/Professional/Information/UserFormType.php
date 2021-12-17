<?php

namespace App\Form\Professional\Information;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
           ;  
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
