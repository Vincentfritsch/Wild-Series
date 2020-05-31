<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = ['Andrew Lincoln', 'Norman Reedus', 'Lauren Cohan', 'Danai Gurira' ];

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        foreach(self::ACTORS as $actors) {
            $actor = new Actor();
            $actor->setName($actors);
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $manager->persist($actor);
            if ($actor->getName() == 'Andrew Lincoln'){
                $actor->addProgram(($this->getReference('program_5')));
            }
            $actor->addProgram($this->getReference('program_0'));
        }
        $faker = Faker\Factory::create('en_US');
        for($i=0; $i<50; $i++)
        {
           $actor = new Actor();
           $actor->setName($faker->name);
           $actor->addProgram($this->getReference('program_' . $i%6));
           $slug = $slugify->generate($actor->getName());
           $actor->setSlug($slug);
           $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}