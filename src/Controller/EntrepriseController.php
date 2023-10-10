<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Cotation;
use App\Form\AvisType;
use App\Entity\Critere;
use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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

    // CE CODE FONCTIONNE MAIS JE VEUX AJOUTER FORMULAIRE CRITERETYPE + NOTES RELIEES
    // //Noter une Entreprise précise
    // #[Route('/entreprise/note/{id}', name: 'entrepriseNote')]
    // public function noterEntreprise(Request $request, ManagerRegistry $doctrine)
    // {
    //     $avisEntreprise = new Avis();
    //     $formulaireNote = $this->createForm(AvisType::class, $avisEntreprise);
    //     $formulaireNote->handleRequest($request);

    //     // obtenir l'User et l'Entreprise
    //     $idEntreprise = $request->get('id');
    //     $em = $doctrine->getManager();

    //     $rep = $em->getRepository(Entreprise::class);
    //     $entreprise = $rep->find($idEntreprise);


    //     $user = $this->getUser();
        
    //     if ($formulaireNote->isSubmitted() && $formulaireNote->isValid()){

    //         // fixer User et Entreprise
    //         $avisEntreprise->setUser($user);
    //         $avisEntreprise->setEntreprise($entreprise);

    //         // stocker l'avis complet
    //         $em->persist($avisEntreprise);
    //         $em->flush();

    //         // rediriger avec le parametre id de lentreprise
    //         return $this->redirectToRoute("entrepriseFiche", ['id' => $idEntreprise]);

    //     }

    //     return $this->render('entreprises/entrepriseNote.html.twig', 
    //     [
    //         'formulaireNote' => $formulaireNote->createView(),
    //         'entreprise' => $entreprise
    //     ]);
    // }

    //Répondre aux questioncritere (entity Critere) par des notes de 0 à 5 (entity Cotation)  
    #[Route('/entreprise/note/{id}', name: 'entrepriseNote')]
    public function critereEntreprise(Request $request, ManagerRegistry $doctrine)
    {
        $avisEntreprise = new Avis();
        $formulaireNote = $this->createForm(AvisType::class, $avisEntreprise);
        $formulaireNote->handleRequest($request);

        // obtenir l'User et l'Entreprise
        $id = $request->get('id');
        $em = $doctrine->getManager();

        $rep = $em->getRepository(Entreprise::class);
        $entreprise = $rep->find($id);

        $rep2 = $em->getRepository(Cotation::class);
        $cotation = $rep2->find($id);


        $user = $this->getUser();
        
        if ($formulaireNote->isSubmitted() && $formulaireNote->isValid()){

            // fixer User et Entreprise
            $avisEntreprise->setUser($user);
            $avisEntreprise->setEntreprise($entreprise);
            //
            $avisEntreprise->setNote($cotation);

            // stocker l'avis complet
            $em->persist($avisEntreprise);
            $em->flush();

            // rediriger avec le parametre id de lentreprise
            return $this->redirectToRoute("entrepriseFiche", ['id' => $id]);

        }

        return $this->render('entreprises/entrepriseNote.html.twig', 
        [
            'formulaireNote' => $formulaireNote->createView(),
            'entreprise' => $entreprise,
            'cotation' => $cotation
        ]);
    }
}