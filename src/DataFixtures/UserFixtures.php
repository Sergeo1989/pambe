<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\ContextService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    private $context;
    
    public function __construct(UserPasswordHasherInterface $encoder, ContextService $context){
        $this->encoder = $encoder;
        $this->context = $context;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('marxiobadel@gmail.com')
             ->setFirstname('Marxio')
             ->setLastname('Badel')
             ->setDateAdd(new \DateTime('now'))
             ->setDateUpd(new \DateTime('now'))
             ->setStatus(true)
             ->setRoles(['ROLE_ADMIN'])
             ->setSlug($this->context->slug($user))
             ->setPassword($this->encoder->hashPassword($user, 'marxio1995'));
        $manager->persist($user);

        $manager->flush();
    }
}
