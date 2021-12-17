<?php

namespace App\Controller\Front;

use App\Entity\Professional;
use App\Form\RegistrationFormType;
use App\Service\ContextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    public function register(ContextService $context, Request $request, UserPasswordHasherInterface $hashPassword): Response
    {
        $professional = new Professional();
        $form = $this->createForm(RegistrationFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();

            $user->setPassword($hashPassword->hashPassword($user, $user->getPassword()));

            $professional->setSlug($context->slug($user));
            
            $context->save($professional);

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/registration/register.html.twig', [
            'registration' => $form->createView(),
        ]);
    }
}
