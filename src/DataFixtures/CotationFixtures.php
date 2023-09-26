<?php

namespace App\DataFixtures;

use App\Entity\Cotation;
use App\Entity\Critere;
use App\Entity\User;
// use App\Entity\Entreprise;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CotationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $repCriteres = $manager->getRepository(Critere::class);
        $arrayCriteres = $repCriteres->findAll();

        $repUsers = $manager->getRepository(User::class);
        $arrayUsers = $repUsers->findAll();


        for ($i = 0; $i < 10; $i++) {
            // Générez des valeurs aléatoires
            $randomUser = $repUsers->find(rand(1, $repUsers->count($arrayUsers)));
            $randomCritere = $repCriteres->find(rand(1, $repCriteres->count($arrayCriteres)));
            
            $randomNote = rand(0, 5);
    
            $cotation = new Cotation([
                'note' => $randomNote,
                'user' => $randomUser,
                'critere' => $randomCritere,
            ]);
    
            $manager->persist($cotation);
        }
    
        $manager->flush();
    }
}