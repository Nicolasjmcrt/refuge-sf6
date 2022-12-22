<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Refuges;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class RefugeFixtures extends Fixture
{

    public function __construct(
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('en_GB');
        
        $refuge = new Refuges();
        $refuge->setName('Au sale cabot');
        $refuge->setAddress($faker->streetAddress);
        $refuge->setZipcode(str_replace(' ', '', $faker->postcode));
        $refuge->setCity($faker->city);
        $refuge->setPhone($faker->phoneNumber);
        $refuge->setEmail($faker->email);
        $refuge->setSlug($this->slugger->slug($refuge->getName())->lower());

        $this->addReference('refuge-1', $refuge);

        $manager->persist($refuge);


        $refuge = new Refuges();
        $refuge->setName('Au poil de carotte');
        $refuge->setAddress($faker->streetAddress);
        $refuge->setZipcode(str_replace(' ', '', $faker->postcode));
        $refuge->setCity($faker->city);
        $refuge->setPhone($faker->phoneNumber);
        $refuge->setEmail($faker->email);
        $refuge->setSlug($this->slugger->slug($refuge->getName())->lower());

        $this->addReference('refuge-2', $refuge);

        $manager->persist($refuge);


        $refuge = new Refuges();
        $refuge->setName('Le greffier de la fourrure');
        $refuge->setAddress($faker->streetAddress);
        $refuge->setZipcode(str_replace(' ', '', $faker->postcode));
        $refuge->setCity($faker->city);
        $refuge->setPhone($faker->phoneNumber);
        $refuge->setEmail($faker->email);
        $refuge->setSlug($this->slugger->slug($refuge->getName())->lower());

        $this->addReference('refuge-3', $refuge);

        $manager->persist($refuge);




        $manager->flush();
    }
}
