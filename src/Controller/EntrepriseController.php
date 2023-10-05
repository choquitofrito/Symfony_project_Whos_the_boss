<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController {

    //DQL: obtenir toutes les entreprises
    #[Route('/entreprises')]
    public function listeEntreprises (ManagerRegistry $doctrine){
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT nom FROM App\Entity\Entreprise nom");
        $res = $query->getResult();
        
        $vars = ['entrepriseListe'=> $res];
        return $this->render ("entreprises/entreprises_liste.html.twig", $vars);
    }
}

