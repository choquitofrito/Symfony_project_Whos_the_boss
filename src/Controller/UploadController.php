<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\CreationEntrepriseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\File\UploadedFile;


//Upload image par l'User -> BD (logo de l'entreprise)
class UploadController extends AbstractController

// {
//     public function __construct (private string $dossierUpload){
//         $this->dossierUpload = $dossierUpload;
//     }

//     public function uploadLogoEntreprise(UploadedFile $fichier): string
//     {

//         // obtenir un nom de fichier unique pour éviter les doublons dans le dossierUpload
//         $nomFichierServeur = md5(uniqid()) . "." . $fichier->guessExtension();
//         // stocker le fichier dans le serveur (on peut préciser encore plus le dossier)
//         $fichier->move($this->dossierUpload . "/logo", $nomFichierServeur);
//         return $nomFichierServeur;
//     }
{

    #[Route("/creation", name:"creationEntreprise")]
    public function uploadEntreprise (Request $req, ManagerRegistry $doctrine)
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

        else
        {
            return $this->redirectToRoute("creationEntreprise");
        }        

        return $this->render("/entreprises/creation.html.twig",
            ['formCreationEntreprise' => $formCreationEntreprise->createView()]);
    }

}