<?php

namespace App\Form\Professional\Edit;

use App\Entity\Professional;
use App\Form\Professional\Information\UserFormType;
use App\Form\ProfessionalImageFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profil', ProfessionalImageFormType::class)
                ->add('cover', ProfessionalImageFormType::class)
                ->add('user', UserFormType::class)
                ->add('skill')
                ->add('category_professionals')
                ->add('languages')
                ->add('short_description', TextareaType::class)
                ->add('description', TextareaType::class)

        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
