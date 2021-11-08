<?php

namespace App\DataFixtures;

use App\Entity\Professional;
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
        $user1 = new User();
        $user1->setEmail('admin1@admin.com')
             ->setFirstname('admin1')
             ->setLastname('admin1')
             ->setDateAdd(new DateTime('now'))
             ->setDateUpd(new DateTime('now'))
             ->setStatus(false)
             ->setPassword($this->encoder->encodePassword($user1, 'admin123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('professional1@professionnel.com')
             ->setFirstname('professionnel1')
             ->setLastname('professionnel1')
             ->setDateAdd(new DateTime('now'))
             ->setDateUpd(new DateTime('now'))
             ->setStatus(false)
             ->setPassword($this->encoder->encodePassword($user2, 'professionnel123'));

        $professionnal1 = new Professional();
        $professionnal1->setUser($user2)
                    ->setDateAdd(new DateTime('now'))
                    ->setDateUpd(new DateTime('now'))
                    ->setStatus(false)
                    ->setVerified(false)
                    ->setShortDescription('My short description')
                    ->setDescription('My long description');
        $manager->persist($user2);
        $manager->persist($professionnal1);
        $manager->flush();
    }
}
