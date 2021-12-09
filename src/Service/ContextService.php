<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContextService
{
    private $security;
    private $slugger;

    public function __construct(Security $security, SluggerInterface $slugger)
    {
        $this->security = $security;
        $this->slugger = $slugger;
    }

    public function getUser(): User
    {    
        return $this->security->getUser();
    }

    public function isAdmin()
    {
        return $this->security->isGranted("ROLE_ADMIN");
    }

    public function isSuperAdmin()
    {
        return $this->security->isGranted("ROLE_SUPER_ADMIN");
    }

    public function hasRole($role)
    {
        return $this->security->isGranted($role);
    }

    public function slug(string $object): string
    {
        setlocale(LC_CTYPE, 'en_US');
        return $this->slugger->slug(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $object)));
    }

    public function sort(array $object, string $column)
    {
        usort($object, function ($a, $b) use ($column)
        {
            if ($a->{'get'.ucfirst($column)}() == $b->{'get'.ucfirst($column)}())
                return 0;
            return ($a->{'get'.ucfirst($column)}() < $b->{'get'.ucfirst($column)}()) ? 1 : -1;
        });

        return $object;
    }
}