<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 5; $i++) {

            
            $user = new User([
                'email' => 'user' . $i . '@gmail.com',
                'roles' => [],
                'nom' => 'nom' . $i,
                'password' => 'monpass'
            ]);
            
            //fixer un password sans hasher 
            $passwordSansHash = "monpassword" . $i;

            //obtenir le password hashé
            $passwordHash = $this->passwordHasher->hashPassword(
                $user, $passwordSansHash
            );

            //incruster dans l'entité le password hashé
            $user->setPassword($passwordHash);


            $manager->persist($user);
        }
        $manager->flush();

        // user ROLE_CHEF et ROLE_ADMIN
        for ($i = 0; $i < 5; $i++) {

            $user = new User([
                'email' => 'userchef' . $i . '@gmail.com',
                'roles' => ["role_chef", "role_admin"],
                'nom' => 'nom' . $i,
                'password' => 'monpass'
            ]);
            
            //fixer un password sans hasher 
            $passwordSansHash = "monpassword" . $i;

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
