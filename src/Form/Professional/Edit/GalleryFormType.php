<?php

namespace App\Form\Professional\Edit;

use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GalleryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('videoType', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices'  => [
                    'Youtube' => Professional::YOUTUBE,
                    'Viméo' => Professional::VIMEO
                ]
            ])
            ->add('videoUrl', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le contenu ne doit pas être vide.'
                    ])
                ]
            ])
            ->add('legend', TextareaType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La légende ne doit pas être vide.'
                    ])
                ]
            ]) 
            ->add('gallery', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class
        ]);
    }
}
