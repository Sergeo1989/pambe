<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin1@admin.com')
             ->setFirstname('admin1')
             ->setLastname('admin1')
             ->setDateAdd(new DateTime('now'))
             ->setDateUpd(new DateTime('now'))
             ->setStatus(false)
             ->setPassword($this->encoder->encodePassword($user, 'admin123'));
        $manager->persist($user);
        $manager->flush();
    }
}
