<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        for ($i = 1; $i <= 1000; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);
            $this->addReference('category_'.$i, $category);

            $program = new Program();
            $program->setTitle($faker->sentence(4, true));
            $program->setSummary($faker->text(100));
            $program->setCategory($this->getReference('category_'.$i));
            $program->setCountry($faker->country);
            $program->setSlug($slugify->generate($program->getTitle()));
            $program->setYear($faker->year($max = 'now'));
            $manager->persist($program);
            $this->addReference('programme_'.$i, $program);

            for($j = 1; $j <= 5; $j ++) {
                $actor = new Actor();
                $actor->setName($faker->name);
                $actor->setSlug($slugify->generate($actor->getName()));
                $manager->persist($actor);
                $actor->addProgram($this->getReference('programme_'.$i));
            }
        }

        $manager->flush();
    }
}
