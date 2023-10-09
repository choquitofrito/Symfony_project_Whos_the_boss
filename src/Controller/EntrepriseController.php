<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntrepriseController extends AbstractController
{

    //DQL: obtenir toutes les entreprises
    #[Route('/entreprises')]
    public function listeEntreprises(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $query = $em->createQuery("SELECT nom FROM App\Entity\Entreprise nom");
        $res = $query->getResult();

        $vars = ['entrepriseListe' => $res];
        return $this->render("entreprises/entreprises_liste.html.twig", $vars);
    }

    //Chemin vers fiche entreprise individuelle
    #[Route('/entreprise/fiche/{id}', name: 'entrepriseFiche')]
    public function ficheEntreprise(
        ManagerRegistry $doctrine,
        Request $req
    ) {
        $em = $doctrine->getManager();

        // obtenir l'entreprise sur laquelle on a clique
        $entreprise = $em->getRepository(Entreprise::class)->find($req->get('id'));

        // $cotations = $entreprise->getCotations();

        $query = $em->createQuery("SELECT avg(cotations.note) FROM App\Entity\Entreprise entreprise
                                    JOIN entreprise.cotations cotations WHERE entreprise = :entreprise");
        
        $query->setParameter('entreprise', $entreprise);
        $res = $query->getResult();
        
        //  dd($res);
        
        $noteMoyenne = null;
        if (count ($res) > 0) {
        $noteMoyenne = $res[0][1];
        } 

        $vars = [
            'entreprise' => $entreprise,
            'noteMoyenne' => $noteMoyenne
        ];

        return $this->render("entreprises/ficheEntreprise.html.twig", $vars);
    }

    //Noter une Entreprise précise
    #[Route('/entreprise/note/{id}', name: 'entrepriseNote')]
    public function noterEntreprise(Request $request, ManagerRegistry $doctrine)
    {
        $avisEntreprise = new Avis();
        $formulaireNote = $this->createForm(Avis::class, $avisEntreprise);
        $formulaireNote->handleRequest($request);

        if ($formulaireNote->isSubmitted() && $formulaireNote->isValid()){
            
        }
    }

}