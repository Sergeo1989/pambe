<?php

namespace App\Form\Professional\Edit;

use App\Entity\Country;
use App\Entity\Professional;
use App\Entity\Region;
use App\Form\Professional\Coordonnee\UserFormType;
use App\Form\SocialFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordonneeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', UserFormType::class)
                ->add('website', TextType::class)
                ->add('country', EntityType::class, [
                    'class' => Country::class,
                    'required' => false
                ])
                ->add('city')
                ->add('socialUrl', SocialFormType::class)
                ->add('save', SubmitType::class)
                ->add('saveAndContinue', SubmitType::class)
        ;
        $builder->addEventListener(
            FormEvents::POST_SET_DATA, function(FormEvent $event)
            {
                $form = $event->getForm();
                $region = $event->getData()->getRegion();
                if($region){
                    $country = $region->getCountry(); 
                    $this->addRegionField($form, $country);
                    $form->get('country')->setData($country);
                }else{
                    $this->addRegionField($form, null);
                }
            }
        );
        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT, function(FormEvent $event)
            {
                $form = $event->getForm();
                $this->addRegionField($form->getParent(), $form->getData());
            }
        ); 
    }

    private function addRegionField(FormInterface $form, ?Country $country)
    {
        $form->add('region', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => $country ? 'Sélectionnez une région' : 'Sélectionnez votre pays',
                'class' => Region::class,
                'required' => false,
                'choices' => $country ? $country->getRegions() : []
            ]
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
