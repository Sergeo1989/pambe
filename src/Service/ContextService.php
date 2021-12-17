<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContextService
{
    private $em;
    private $security;
    private $slugger;

    public function __construct(EntityManagerInterface $em, Security $security, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->security = $security;
        $this->slugger = $slugger;
    }

    public function save(object $object){
        $this->em->persist($object);
        $this->em->flush();
        return $object;
    }

    public function delete(object $object){
        $this->em->remove($object);
        $this->em->flush();
    }

    public function getUser(): User
    {    
        return $this->security->getUser();
    }

    public function isAdmin(): bool
    {
        return $this->security->isGranted("ROLE_ADMIN");
    }

    public function isSuperAdmin(): bool
    {
        return $this->security->isGranted("ROLE_SUPER_ADMIN");
    }

    public function hasRole($role): bool
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