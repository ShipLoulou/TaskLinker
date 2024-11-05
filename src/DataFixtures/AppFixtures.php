<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($index = 0; $index < 2; $index++) {
            $project = new Project();
            $project->setName($faker->realText(mt_rand(20, 50)))
                ->setStartDate($faker->dateTimeBetween('-6 months', 'now'))
                ->setDeadline($faker->dateTimeBetween('now', '+6 months'))
                ->setArchive($faker->boolean((33)));
            $manager->persist($project);
        }

        $manager->flush();
    }
}
