<?php

namespace App\Form\Professional\Edit;

use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                    'Viméo' => Professional::VIMEO,
                ],
            ])
            ->add('videoUrl', TextType::class, [
                'required' => true
            ])
            ->add('gallerie', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
