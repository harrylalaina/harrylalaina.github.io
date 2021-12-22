<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Mesure;
use App\Entity\NearMiss;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Faker;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 4; $i++) {
            $employe = new Employe;
            $employe->setName($faker->name())
                ->setService($faker->company())
                ->setNomResponsable($faker->name());
            $manager->persist($employe);

            for ($j = 1; $j <= mt_rand(3, 6); $j++) {
                $nearmiss = new NearMiss;
                $nearmiss->setTitre($faker->sentence())
                    ->setDescription($faker->paragraph())
                    ->setActionImmediate($faker->paragraph())
                    ->setNiveauRisque($faker->sentence())
                    ->setEmploye($employe);
                $manager->persist($nearmiss);
            }
        }


        $manager->flush();
    }
}
