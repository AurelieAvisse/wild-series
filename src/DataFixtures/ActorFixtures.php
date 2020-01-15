<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    const ACTORS = [
        'Blabla Blabg',
        'Test Testg',
        'Riri Riyhgf'
    ];

    public function load(ObjectManager $manager)
    {

        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);

            $manager->persist($actor);
        }

        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 50; $i++) {

            $actor = new Actor();

            $actor->setName($faker->name());

            $actor->addProgram($this->getReference('program_' . rand(0, count(ProgramFixtures::PROGRAMS) - 1)));

            $manager->persist($actor);

            $this->addReference('actor_' . $i, $actor);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

}