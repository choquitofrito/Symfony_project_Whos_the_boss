<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AvisFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {

            
            $commentaire = new Commentaire([

            ]);
            

            $manager->persist($commentaire);
        }
        $manager->flush();
    }
}