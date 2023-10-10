<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Entreprise;
use App\Form\AvisType;
use App\Form\CreationEntrepriseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $formulaireNote = $this->createForm(AvisType::class, $avisEntreprise);
        $formulaireNote->handleRequest($request);

        // obtenir l'User et l'Entreprise
        $idEntreprise = $request->get('id');
        $em = $doctrine->getManager();
        $rep = $em->getRepository(Entreprise::class);
        $entreprise = $rep->find($idEntreprise);

        $user = $this->getUser();
        
        if ($formulaireNote->isSubmitted() && $formulaireNote->isValid()){

            // fixer User et Entreprise
            $avisEntreprise->setUser($user);
            $avisEntreprise->setEntreprise($entreprise);

            // stocker l'avis complet
            $em->persist($avisEntreprise);
            $em->flush();

            // rediriger avec le parametre id de lentreprise
            return $this->redirectToRoute("entrepriseFiche", ['id' => $idEntreprise]);

        }

        return $this->render('entreprises/entrepriseNote.html.twig', 
        [
            'formulaireNote' => $formulaireNote->createView(),
            'entreprise' => $entreprise
        ]);
    }
}

//Upload image par l'User -> BD (logo de l'entreprise)
class UploadHelper

{
    public function __construct (private string $dossierUpload){
        $this->dossierUpload = $dossierUpload;
    }

    public function uploadLogoEntreprise(UploadedFile $fichier): string
    {

        // obtenir un nom de fichier unique pour éviter les doublons dans le dossierUpload
        $nomFichierServeur = md5(uniqid()) . "." . $fichier->guessExtension();
        // stocker le fichier dans le serveur (on peut préciser encore plus le dossier)
        $fichier->move($this->dossierUpload . "/logo", $nomFichierServeur);
        return $nomFichierServeur;
    }


    #[Route("/entreprise/creation", name:"creationEntreprise")]
    public function upload (Request $req, ManagerRegistry $doctrine)
    {
        $entreprise = new Entreprise();
        $formCreationEntreprise = $this->createForm(CreationEntrepriseType::class, $entreprise);
        $formCreationEntreprise->handleRequest($req);

        if ($formCreationEntreprise->isSubmitted() && $formCreationEntreprise->isValid()) 
        {
            $fichier = $formCreationEntreprise['image']->getData();
            $dossier = $this->getParameter('kernel.project_dir').'/public/dossierLogos';
        
            if ($fichier) {
                $nomFichierServeur = md5(uniqid()) . "." . $fichier->guessExtension();
                $fichier->move($dossier,$nomFichierServeur);
                $entreprise->setImage($nomFichierServeur);
            }

            $em = $doctrine->getManager();
            $em->persist($entreprise);
            $em->flush();

            return new Response ("Le fichier à bien été ajouté.");
        }

        else{
            return $this->render("/entreprises/creation.html.twig",
            ['formulaireCreation' => $formCreationEntreprise]
        );
        }
    }
}