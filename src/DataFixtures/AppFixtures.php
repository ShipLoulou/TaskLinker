<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Project;
use App\Entity\Employee;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $projects = [];

        for ($index = 0; $index < 2; $index++) {
            $project = new Project();
            $project->setName($faker->realText(mt_rand(20, 50)))
                ->setStartDate($faker->dateTimeBetween('-6 months', 'now'))
                ->setDeadline($faker->dateTimeBetween('now', '+6 months'))
                ->setArchive($faker->boolean((33)));

            $projects[] = $project;

            $manager->persist($project);
        }

        $typesContract = ['CDI', 'CDD', 'Freelance'];

        for ($index = 0; $index < 3; $index++) {
            $employee = new Employee();
            $hash = $this->encoder->hashPassword($employee, 'password');
            $employee->setEmail($faker->email())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setRoles(['ROLE_EMPLOYEE'])
                ->setContract($faker->randomElement($typesContract))
                ->setArrivalDate($faker->dateTimeBetween('-9 months', 'now'))
                ->setActive($faker->boolean((75)))
                ->setPassword($hash);

            $selectedProjects = $faker->randomElements($projects, mt_rand(1, 2));

            foreach ($selectedProjects as $project) {
                $employee->addProject($project);
            }

            $manager->persist($employee);
        }

        $manager->flush();
    }
}
