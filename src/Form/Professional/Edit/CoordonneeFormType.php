<?php

namespace App\Form\Professional\Edit;

use App\Entity\Professional;
use App\Form\Professional\Coordonnee\UserFormType;
use App\Form\SocialFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordonneeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', UserFormType::class)
                ->add('website', TextType::class)
                ->add('country')
                ->add('region')
                ->add('city')
                ->add('socialUrl', SocialFormType::class)
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
