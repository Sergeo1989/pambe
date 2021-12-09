<?php

namespace App\Security\Admin;

use App\Entity\User;
use App\Service\ContextService;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminChecker implements UserCheckerInterface
{
    private $context;

    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof User)
            return;

        if($user->getAdmin() && !$user->getAdmin()->getStatus())
            throw new CustomUserMessageAccountStatusException('Votre compte a été bloqué.');
    }

    public function checkPostAuth(UserInterface $user)
    {
        if(!$user instanceof User)
            return;
            
        if($this->context->isAdmin())
            throw new CustomUserMessageAccountStatusException('Votre n\'avez pas le droit d\'accéder  à l\'administration.');
    }

   
}