<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class ContextService
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser(): User
    {    
        return $this->security->getUser();
    }

    public function isAdmin()
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    public function isSuperAdmin()
    {
        return $this->security->isGranted('ROLE_SUPER_ADMIN');
    }

    public function hasRole($role)
    {
        return $this->security->isGranted($role);
    }
}