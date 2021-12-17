<?php

namespace App\Form\Professional\Edit;

use App\Entity\Professional;
use App\Form\ServiceFormType as ServiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('services', CollectionType::class, [
            'entry_type' => ServiceType::class,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
