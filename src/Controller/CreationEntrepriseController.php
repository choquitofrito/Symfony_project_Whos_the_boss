<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreationEntrepriseController extends AbstractController
{
    #[Route('/creation/entreprise', name: 'creation_entreprise')]
    public function creationEntreprise()
    {
        // on crée le formulaire du type souhaité
        $creationEntreprise = $this->createForm(CreationType::class);

        // on envoie un objet FormView à la vue pour qu'elle puisse 
        // faire le rendu, pas le formulaire en soi
        $vars = ['unFormulaire' => $creationEntreprise];

        return $this->render('/creation_entreprise/creation.html.twig', $vars);
    }
}
