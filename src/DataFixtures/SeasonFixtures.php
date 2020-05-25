<?php


namespace App\DataFixtures;



use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i=0; $i<50; $i++)
        {
            $season = new Season();
            $season->setNumber($faker->randomDigitNotNull);
            $season->setDescription($faker->text);
            $season->setYear($faker->year($max='now'));
            $season->setProgram($this->getReference('program_' . $i%6));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}