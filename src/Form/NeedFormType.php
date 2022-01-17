<?php

namespace App\Form;

use App\Entity\CategoryProfessional;
use App\Entity\Need;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Vich\UploaderBundle\Form\Type\VichFileType;

class NeedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le titre ne doit pas être vide.',
                        ])
                    ]
                ])
                ->add('category', EntityType::class, [
                    'class' => CategoryProfessional::class,
                    'expanded' => false,
                    'multiple' => false
                ])
                ->add('description', TextareaType::class, [
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
                ->add('budget', NumberType::class, [
                    'constraints' => [
                        new Type([
                            'type' => 'float',
                            'message' => 'La valeur {{ value }} n\'est un nombre.'
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
                ->add('documentFile', VichFileType::class, [
                    'required' => false,
                    'allow_delete' => true,
                    'delete_label' => 'Supprimer l\'image',
                    'download_uri' => true,
                    'download_label' => true,
                    'asset_helper' => true,
                ])
                ->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Need::class
        ]);
    }
}
