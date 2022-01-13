<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPassFormType extends AbstractType
{
    private $translate;

    public function __construct(TranslatorInterface $translate)
    {
        $this->translate = $translate;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('email', EmailType::class, [
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new Email([
                            'message' => 'L\'e-mail {{ value }} n\'est pas valide.',
                        ]),
                        new NotBlank([
                            'message' => 'L\'e-mail ne doit pas être vide.',
                        ])
                    ]
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
