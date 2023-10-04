<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EntrepriseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $arraySecteur = ['Agroalimentaire', 'Banque/Assurance', 'Bois/Papier/Imprimerie', 'BTP', 'Chimie', 'Commerce/Distribution', 'Édition/Communication', 'Electronique', 'Études et Conseils', 'Industrie pharmaceutique', 'Informatique', 'Équipements/Automobile', 'Metallurgie', 'Plastique', 'Services', 'Textile', 'Transport/Logistique'];

        for ($i = 0; $i < 10; $i++) {

            $randomSecteurIndex = mt_rand(0, count($arraySecteur)-1);
            $randomSecteur = $arraySecteur[$randomSecteurIndex];

            $entreprise = new Entreprise([
                'nom' => $faker->company,
                'secteur' => $randomSecteur,
                'emplacement' => $faker->address,
                'image' => $faker->imageUrl,
                'description' => $faker->realText()
            ]);

            $manager->persist($entreprise);
        }

        $manager->flush();
    }
}