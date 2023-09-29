<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {

            //fixer un password sans hasher 
            $passwordSansHash = "monpassword" . $i;
            
            $user = new User([
                'email' => 'user' . $i . '@gmail.com',
                'roles' => [],
                'nom' => $faker->name(),
                'password' => $passwordSansHash
            ]);
            

            //obtenir le password hashé
            $passwordHash = $this->passwordHasher->hashPassword(
                $user, $passwordSansHash
            );

            //incruster dans l'entité le password hashé
            $user->setPassword($passwordHash);


            $manager->persist($user);
        }
        $manager->flush();
    }
}
