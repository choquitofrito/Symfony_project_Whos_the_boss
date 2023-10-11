<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Critere;
use App\Entity\Cotation;

use App\Entity\Entreprise;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CotationFixtures extends Fixture
implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create();

        $repCriteres = $manager->getRepository(Critere::class);
        $arrayCriteres = $repCriteres->findAll();

        $repUsers = $manager->getRepository(User::class);
        $arrayUsers = $repUsers->findAll();

        $repEntreprise = $manager->getRepository(Entreprise::class);
        $arrayEntreprises = $repEntreprise->findAll();


        for ($i = 0; $i < 10; $i++) {
            // valeurs aléatoires
            $randomUser = $arrayUsers[array_rand($arrayUsers)];
            $randomEntreprise = $arrayEntreprises[array_rand($arrayEntreprises)];
            $randomCritere = $arrayCriteres[array_rand($arrayCriteres)];

            $randomNote = rand(0, 5);

            $cotation = new Cotation([
                'note' => $randomNote,
                'commentaire' => $faker->text(),
            ]);

            $manager->persist($cotation);

            $randomCritere->addCotation($cotation);
            $randomEntreprise->addCotation($cotation);
            $randomUser->addCotation($cotation);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            EntrepriseFixtures::class,
            UserFixtures::class,
            CritereFixtures::class
        ]);
    }
}
