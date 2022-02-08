<?php

namespace App\Controller\Front;

use App\Form\ResetPassFormType;
use App\Repository\UserRepository;
use App\Service\ContextService;
use App\Service\MailerService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private $context;
    private $translator;
    private $emailsender;

    public function __construct(ContextService $context, TranslatorInterface $translator, $emailSender)
    {
        $this->context = $context;
        $this->translator = $translator;
        $this->emailsender = $emailSender;
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function forgottenPass(Request $request, UserRepository $userRepo, MailerService $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(ResetPassFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepo->findOneByEmail($email);

            if(!$user){
                $message = $this->translator->trans('global.this_address_does_not_exist.');
                $this->addFlash('danger', $message);
                return $this->redirectToRoute('app_login');
            }

            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $user = $this->context->save($user);
            }catch(\Exception $e){
                $message = $this->translator->trans('global.an_error_occurred:');
                $this->addFlash('warning', $message . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $subject = $this->translator->trans('global.forgotten_password');
            $mailer->send(
                $subject, 
                $this->emailsender, 
                $user->getEmail(), 
                'front/email/reset_password.html.twig', 
                ['url' => $url]
            );

            $message = $this->translator->trans('global.a_password_reset_email_has_been_sent_to_you.');
            $this->addFlash('message', $message);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/security/forgotten_password.html.twig', ['forgotForm' => $form->createView()]);
    }

    public function resetPass($token, Request $request, UserRepository $userRepo, UserPasswordHasherInterface $hashPassword)
    {
        $user = $userRepo->findOneBy(['reset_token' => $token]);

        if(!$user) {
            $message = $this->translator->trans('global.unknown_token');
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('app_login');
        }

        if($request->isMethod('POST')){
            $user->setResetToken(null);

            $user->setPassword($hashPassword->hashPassword($user, $request->request->get('password')));
            $this->context->save($user);

            $message = $this->translator->trans('global.password_successfully_changed');
            $this->addFlash('message', $message);

            return $this->redirectToRoute('app_login');
        }else{
            return $this->render('front/security/reset_password.html.twig', compact('token'));
        }
    }

    public function google(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('google')->redirect([], []);
    }

    public function facebook(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('facebook')->redirect([], []);
    }

    public function linkedin(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('linkedin')->redirect([], []);
    }
}
