<?php

namespace App\Form;

use App\Entity\TariffOption;
use App\Entity\TariffTariffOption;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TariffOptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('available', CheckboxType::class, [
                'label' => 'Disponible ?',
            ])
            ->add('tariffOption', EntityType::class, [
                'label' => 'Option',
                'class' => TariffOption::class
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TariffTariffOption::class
        ]);
    }
}
