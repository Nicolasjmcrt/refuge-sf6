<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class StatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $status = new Status();
        $status->setName('Perdu');
        $manager->persist($status);

        $this->addReference('status-1', $status);

        $status = new Status();
        $status->setName('En refuge');
        $manager->persist($status);

        $this->addReference('status-2', $status);

        $status = new Status();
        $status->setName('Adopté');
        $manager->persist($status);

        $this->addReference('status-3', $status);

        $manager->flush();
    }
}
