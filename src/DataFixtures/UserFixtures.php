<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        /*$user1 = new User();
        $user1->setEmail('admin@admin.com')
             ->setFirstname('admin')
             ->setLastname('admin')
             ->setDateAdd(new DateTime('now'))
             ->setDateUpd(new DateTime('now'))
             ->setStatus(false)
             ->setRoles(['ROLE_ADMIN'])
             ->setSlug('admin-admin-com')
             ->setPassword($this->encoder->hashPassword($user1, 'admin123'));
        $manager->persist($user1);

        $manager->flush();*/
    }
}
