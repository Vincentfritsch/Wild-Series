<?php


namespace App\DataFixtures;


use App\Entity\Episode;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i=0; $i<500; $i++)
        {
            $episode = new Episode();
            $episode->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
            $episode->setNumber($faker->randomDigitNotNull);
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . $i%50));
            $manager->persist($episode);
        }
        $manager->flush();
    }


        public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}