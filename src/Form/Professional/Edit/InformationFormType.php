<?php

namespace App\Form\Professional\Edit;

use App\Entity\Professional;
use App\Form\ProfessionalImageFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationFormType extends AbstractType
{
    private $profile;

    public function __construct($profile)
    {
        $this->profile = $profile;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profile', ChoiceType::class, [
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => $this->profile
                ])
                ->add('cover', ProfessionalImageFormType::class)
                ->add('skill')
                ->add('category_professionals')
                ->add('languages')
                ->add('short_description', TextareaType::class)
                ->add('description', TextareaType::class)
                ->add('save', SubmitType::class)
                ->add('saveAndContinue', SubmitType::class)

        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
