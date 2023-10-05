<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreationEntrepriseController extends AbstractController
{
    #[Route('/creation/entreprise', name: 'creation_entreprise')]
    public function creationEntreprise(Request $req, ManagerRegistry $doctrine)
    {
        $creationEntreprise = new CreationEntreprise();
        $form = $this->createForm(creationEntreprise::class, $creationEntreprise);

        $form->handleRequest($req);

        if ($this->getUser()) {
            
        }
    }
}
