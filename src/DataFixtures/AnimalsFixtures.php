<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Animals;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnimalsFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('en_GB');

        for ($i=1; $i <= 10; $i++) { 
            $ani = new Animals();
            $ani->setName($faker->firstName);
            $ani->setDescription($faker->text);
            $ani->setSlug($this->slugger->slug($ani->getName())->lower());
            $sex = $this->getReference('sex-'.rand(1, 2));
            $ani->setSex($sex);
            while( in_array( ($n = mt_rand(1,14)), array(1, 8)));
            $race = $this->getReference('race-'.$n);
            $ani->setRaces($race);
            $status = $this->getReference('status-'.rand(1, 3));
            $ani->setStatus($status);

            $this->setReference('ani-'.$i, $ani);
            
            
            $manager->persist($ani);
        }
        

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SexFixtures::class,
            StatusFixtures::class,
            RacesFixtures::class,
            RefugeFixtures::class,
        ];
    }
}
