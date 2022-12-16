<?php

namespace App\DataFixtures;

use App\Entity\Sex;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SexFixtures extends Fixture
{
    
    public function load(ObjectManager $manager): void
    {
        $sex = new Sex();
        $sex->setName('Male');
        $manager->persist($sex);

        $this->addReference('sex-1', $sex);

        $sex = new Sex();
        $sex->setName('Female');
        $manager->persist($sex);

        $this->addReference('sex-2', $sex);

        $manager->flush();
    }
}
