<?php

namespace App\Form;

use App\Entity\CategoryProfessional;
use App\Entity\Language;
use App\Entity\Professional;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessionalFormType extends AbstractType
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
                ->add('skill', EntityType::class, [
                    'class' => Skill::class,
                    'expanded' => false,
                    'multiple' => false
                ])
                ->add('category_professionals', EntityType::class, [
                    'class' => CategoryProfessional::class,
                    'expanded' => false,
                    'multiple' => true
                ])
                ->add('languages', EntityType::class, [
                    'class' => Language::class,
                    'expanded' => false,
                    'multiple' => true
                ])
                ->add('short_description', TextareaType::class)
                ->add('description', TextareaType::class)
                ->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
