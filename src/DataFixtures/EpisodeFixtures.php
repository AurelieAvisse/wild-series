<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 100; $i++) {
            $episode = new Episode();

            $episode->setNumber($faker->numberBetween(1, 5));
            $episode->setTitle($faker->word());
            $episode->setSynopsis($faker->realText());

            $episode->setSeason($this->getReference('season_' . random_int(0, 49)));

            $manager->persist($episode);

            $this->addReference('episode_' . $i, $episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}