<?php

namespace App\Security\Front;

use App\Entity\User;
use App\Security\Front\Exception\NotVerifiedEmailException;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\LinkedInResourceOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LinkedInAuthenticator extends OAuth2Authenticator
{
    use TargetPathTrait; 

    private $clientRegistry;
    private $em;
    private $router;
    private $mailer;
    private $encoder;
    private $emailSender;
    private $clientName = 'linkedin';

    public function __construct($emailSender, MailerService $mailer, UserPasswordHasherInterface $encoder, ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
	    $this->router = $router;
        $this->emailSender = $emailSender;
        $this->mailer = $mailer;
        $this->encoder = $encoder;
    }

    public function supports(Request $request): ?bool
    { 
        return $request->attributes->get('_route') === 'connect_service_check' && $request->get('service') === $this->clientName;
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient($this->clientName);
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client, $request) {
                /** @var LinkedInResourceOwner $linkedInUser */
                $linkedInUser = $client->fetchUserFromToken($accessToken);

                $email = $linkedInUser->getEmail();
                if($email == null){
                    throw new NotVerifiedEmailException();
                }

                $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

                if (!$user) {
                    $password = uniqid();
                    $user = new User();
                    $user->setEmail($email)
                        ->setFirstname($linkedInUser->getFirstName() ?? '')
                        ->setLastname($linkedInUser->getLastName() ?? 'ChangeMe')
                        ->setPassword($this->encoder->hashPassword($user, $password));

                    $this->em->persist($user);
                    $this->em->flush();

                    $this->mailer->send(
                        'Identifiants', 
                        $this->emailSender, 
                        $email, 
                        'front/email/identifiers.html.twig', 
                        ['email' => $email, 'password' => $password]
                    );
                
                    /** @var Session $session */
                    $session = $request->getSession();
                    $session->getFlashBag()->add('info', 'Enregistrement effectué avec succès. Veuillez consulter votre boite e-mail pour récupérer vos identifiants.');
                }

                return $user;
            })
        );
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if($request->hasSession())
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->router->generate('app_login'));
    }

}