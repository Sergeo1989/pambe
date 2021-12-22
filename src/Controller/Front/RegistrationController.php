<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\ContextService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    private $emailsender;

    public function __construct($emailSender)
    {
        $this->emailsender = $emailSender;
    }

    public function register(ContextService $context, Request $request, UserPasswordHasherInterface $hashPassword, MailerService $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();

            $user->setPassword($hashPassword->hashPassword($user, $password));

            $user->setRoles(array('ROLE_USER'));

            $token = md5(uniqid());
            $user->setActivationToken($token);

            $context->save($user);

            $mailer->send(
                'Activation de compte', 
                $this->emailsender, 
                $user->getEmail(), 
                'front/email/activation.html.twig', 
                ['token' => $token]
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/registration/register.html.twig', [
            'registration' => $form->createView(),
        ]);
    }

    public function activation($token, UserRepository $userRepo, ContextService $context)
    {
        $user = $userRepo->findOneBy(['activation_token' => $token]);

        if(!$user)
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        
        $user->setActivationToken(null);
        $context->save($user);

        $this->addFlash("message", "Vous avez bien activé votre compte");

        return $this->redirectToRoute('app_home');
    }
}
