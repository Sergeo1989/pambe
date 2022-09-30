<?php

namespace App\Security\Front;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OtherChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        $this->checkAuth($user);
    }

    public function checkPostAuth(UserInterface $user)
    {
        $this->checkAuth($user);
    }

    private function checkAuth(UserInterface $user)
    {
        if (!$user instanceof User)
            return;
        
        if ($user->getAdmin())
            throw new CustomUserMessageAccountStatusException('Désolé, en tant qu\'admin, vous devez vous muni d\'un compte client pour accéder à cette partie du site.');

        if (!$user->getStatus())
            throw new CustomUserMessageAccountStatusException('Votre compte a été bloqué.');
    }
}