<?php

namespace App\Controller\Front;

use App\Entity\User;
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
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user->setPassword($hashPassword->hashPassword($user, $user->getPassword()));

            $context->save($user);

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/registration/register.html.twig', [
            'registration' => $form->createView(),
        ]);
    }
}
