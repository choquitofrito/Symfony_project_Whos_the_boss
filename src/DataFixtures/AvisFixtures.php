<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Entreprise;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\EntrepriseFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $users = $manager->getRepository(User::class)->findAll();
        $entreprises = $manager->getRepository(Entreprise::class)->findAll();

        $faker = Factory::create();



        for ($i = 1; $i <= 10; $i++) {

            $user = $users[array_rand($users)];
            $entreprise = $entreprises[array_rand($entreprises)];

            $commentaire = $faker->realText(800);
            $date = $faker->dateTimeThisYear;

            $avis = new Avis([
                'commentaire' => $commentaire,
                'date' => $date,

            ]);

            $user->addAvis($avis);
            $entreprise->addAvis($avis);
            $manager->persist($avis);
        }



        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            EntrepriseFixtures::class,
            UserFixtures::class,
        ]);
    }
}
