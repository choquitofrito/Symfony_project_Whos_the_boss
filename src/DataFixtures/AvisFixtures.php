<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Entreprise;

class AvisFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Récupérez tous les utilisateurs et entreprises existants depuis la base de données
        $users = $manager->getRepository(User::class)->findAll();
        $entreprises = $manager->getRepository(Entreprise::class)->findAll();

        // Vérifiez si les tableaux ne sont pas vides avant d'utiliser array_rand()
        if (!empty($users) && !empty($entreprises)) {
            // Créez des avis en associant aléatoirement des utilisateurs et des entreprises
            for ($i = 1; $i <= 10; $i++) {
                
                $user = $users[array_rand($users)]; // Sélectionnez un utilisateur aléatoire
                $entreprise = $entreprises[array_rand($entreprises)]; // Sélectionnez une entreprise aléatoire

                $commentaire = $faker->text(800);
                $date = $faker->dateTimeThisYear;

                $avis = new Avis([
                    'commentaire' => $commentaire,
                    'date' => $date,
                    'user' => $user,
                    'entreprise' => $entreprise,
                ]);

                $manager->persist($avis);
            }
        } else {
            // Gérez le cas où les tableaux sont vides (par exemple, affichez un message d'erreur)
            // ...
        }

        $manager->flush();
    }
}

