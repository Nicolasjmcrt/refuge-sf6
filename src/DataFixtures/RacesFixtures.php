<?php

namespace App\DataFixtures;

use App\Entity\Races;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class RacesFixtures extends Fixture
{

    private $counter = 1;

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {

        $parent = $this->createRace('Chats', null, $manager);

        $this->createRace('Siamois', $parent, $manager);
        $this->createRace('Bengal', $parent, $manager);
        $this->createRace('Birman', $parent, $manager);
        $this->createRace('Chartreux', $parent, $manager);
        $this->createRace('Himalayen', $parent, $manager);
        $this->createRace('Maine Coon', $parent, $manager);

        $parent = $this->createRace('Chiens', null, $manager);

        $this->createRace('Berger Allemand', $parent, $manager);
        $this->createRace('Bouledogue Français', $parent, $manager);
        $this->createRace('Bouvier Bernois', $parent, $manager);
        $this->createRace('Bouvier des Flandres', $parent, $manager);
        $this->createRace('Bouvier des Ardennes', $parent, $manager);
        $this->createRace('Bouvier des Pyrénées', $parent, $manager);


        $manager->flush();
    }


    public function createRace(string $name, Races $parent = null, ObjectManager $manager)
    {
        $race = new Races();
        $race->setName($name);
        $race->setSlug($this->slugger->slug($race->getName())->lower());
        $race->setParent($parent);
        $manager->persist($race);

        $this->addReference('race-'.$this->counter, $race);
        $this->counter++;

        return $race; 
    }
}
