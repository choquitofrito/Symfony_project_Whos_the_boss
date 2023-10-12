<?php

namespace App\Controller;

use App\Entity\Critere;
use App\Entity\Cotation;
use App\Form\CotationType;
use App\Repository\CritereRepository;
use App\Repository\CotationRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CotationController extends AbstractController
{


    // exemple d'action pour afficher et traiter le form des cotations pour chaque critére
    // Cette action sera appelée en cliquant le bouton "Donner une note"
    #[Route('/cotation/{id}', requirements: ['id' => '\d+'])] // requirements (id doit être numérique) pour que cette route ne se confonde pas avec /cotation/enregistrer plus bas
    public function formsCotations(
        Request $req,
        EntrepriseRepository $entRep,
        CritereRepository $criRep
    ): Response {
        // il faut que cette action reçoit l'id de l'entreprise à côter 
        $id = $req->get('id');
        $entreprise = $entRep->find($id);

        // il faut fixer l'User plus tard pour la Cotation
        $user = $this->getUser();

        // obtenir tous les critéres
        $criteres = $criRep->findAll();


        // nous allons créer autant de forms comme de critéres et les envoyer à la vue pour les afficher. 
        // UNE AUTRE ACTION les traitera car on utilisera AJAX et le submit de chaque form est indépendant
        $forms = [];
        foreach ($criteres as $critere) {
            // Pour chaque Critere dans cette Entreprise on stockera une cotation. 
            // L'user et l'entreprise sont déjà choisies quand on arrive à cette action alors en les fixe dans la Cotation 
            // Maintenant il manque juste que l'User remplisse les données de la cotation
            // On peut cacher 
            $cotation = new Cotation();
            $cotation->setCritere($critere);
            $cotation->setEntreprise($entreprise);
            $cotation->setUser($user);


            // on crée un form avec notre classe (CotationType) et on fixe l'action qui traite le form
            $form = $this->createForm(
                CotationType::class,
                $cotation
                
            );


            $forms[] = $form->createView(); // normallement on renvoie uniquement l'objet $form à la vue et 
            // form_start lance createView en cachette, mais c'est uniquement possible 
            // quand on renvoie un seul formulaire. Ici on doit envoyer l'array des formViews, pas juste les forms
        };

        return $this->render('cotation/forms_cotation.html.twig', [
            'entreprise' => $entreprise,
            'forms' => $forms,
        ]);
    }

    // cette action enregistre la cotation déjà complete. Elle ne renvoie pas une vue, sinon un JSON pour indiquer si tout ok ou pas
    #[Route('/cotation/enregistrer', name: 'enregistrer_cotation')]
    public function enregistrerCotation(
        Request $req,
        EntrepriseRepository $entRep,
        ManagerRegistry $doctrine
    ) {

        
        $cotation = new Cotation();
        $form = $this->createForm(
            CotationType::class,
            $cotation
        );

        $form->handleRequest($req);
        // dd ($cotation);
        
        $entityManager = $doctrine->getManager();
        
        $entityManager->persist($cotation);
        //  dd($cotation);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Cotations enregistrées avec succès']);
    }
}
