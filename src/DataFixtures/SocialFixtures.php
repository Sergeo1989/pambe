<?php

namespace App\DataFixtures;

use App\Entity\SocialUrl;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SocialFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $social = new SocialUrl();
        $social->setFacebook('#')->setInstagram('#')
                ->setLinkedin('#');
        $manager->persist($social);
        $manager->flush();
    }
}
