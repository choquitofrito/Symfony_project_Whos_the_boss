<?php

namespace App\DataFixtures;

use App\Entity\Cotation;
use Faker\Factory;
use App\Entity\Critere;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CritereFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $repCotation = $manager->getRepository(Cotation::class);
        $arrayCotation = $repCotation->findAll();


        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {

                $critere = new Critere();
                $critere->setQuestioncritere($faker->text(120));

            if (!empty($arrayCotation)) {
                $randomCotation = $arrayCotation[array_rand($arrayCotation)];
                $critere->addCotation($randomCotation);
        }
            
            $manager->persist($critere);
        }

        $manager->flush();
    }

}