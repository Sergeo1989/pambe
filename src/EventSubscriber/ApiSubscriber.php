<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $translator;
    private $encoder;
    private $emailsender;
    
    public function __construct(MailerService $mailer, TranslatorInterface $translator, UserPasswordHasherInterface $encoder, $emailSender)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->encoder = $encoder;
        $this->emailsender = $emailSender;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['encodePassword', EventPriorities::PRE_WRITE],
                ['sendMailToUser', EventPriorities::POST_WRITE]
            ]
        ];
    }

    public function encodePassword(ViewEvent $event) 
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof User && $method === Request::METHOD_POST) {
            $hash = $this->encoder->hashPassword($result, $result->getPassword());
            $token = md5(uniqid());
            $result->setActivationToken($token);
            $result->setPassword($hash);
        }
    }

    public function sendMailToUser(ViewEvent $event) 
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof User && $method === Request::METHOD_POST) {
            $subject = $this->translator->trans('global.account_activation');

            $this->mailer->send(
                $subject, 
                $this->emailsender, 
                $result->getEmail(), 
                'front/email/activation.html.twig', 
                ['token' => $result->getActivationToken()]
            );
        }
    }
}