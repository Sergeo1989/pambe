<?php

namespace App\Form;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('thumbnailFile', VichImageType::class, [
                'label' => 'Mise en avant',
                'help' => 'Résolution: 1200x300 pixels',
                'constraints' => [
                        new Image([
                            'minWidth' => 1200,
                            'minHeight' => 300,
                            'minWidthMessage' => 'La largeur de l\'image est trop petite',
                            'minHeightMessage' => 'La hauteur de l\'image est trop petite'
                            ])
                    ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'invalid_message' => 'La valeur que vous avez entrez n\'est pas valide',
                'currency' => 'XAF',
                'scale' => 0
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité',
            ])
            ->add('description', TextEditorType::class, [
                'label' => 'Description',
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
