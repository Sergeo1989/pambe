<?php

namespace App\Form;

use App\Entity\Proposal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProposalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('price', NumberType::class, [
                    'constraints' => [
                        new GreaterThanOrEqual([
                            'value' => 3000
                        ])
                    ]
                    ])
                ->add('note', TextareaType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'La description ne doit pas être vide.',
                        ]),
                        new Length([
                            'min' => 200,
                            'minMessage' => 'Votre description doit avoir au moins {{ limit }} caractères',
                            'max' => 5000,
                            'maxMessage' => 'Votre description ne doit pas dépasser les {{ limit }} caractères'
                        ])
                    ]
                ])
                ->add('delay', IntegerType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le délai ne doit pas être vide.',
                        ])
                    ]
                ])
                ->add('save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proposal::class
        ]);
    }
}
