<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Images;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($img=1; $img <= 100; $img++) { 
            $image = new Images();
            $image->setName($faker->image(null, 640, 480));
            $animals = $this->getReference('ani-'.rand(1, 10));
            $image->setAnimals($animals);
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalsFixtures::class,
        ];
    }
}
