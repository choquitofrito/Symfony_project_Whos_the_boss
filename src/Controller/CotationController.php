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
            // Pour chaque Critere on stockera une cotation. L'user et l'entreprise sont déjà choisies maintenant
            // il manque juste que l'User remplisse les données de la cotation
            $cotation = new Cotation();
            $cotation->setCritere($critere);

            $cotation->setEntreprise($entreprise);
            $cotation->setUser($user);


            // on crée un form avec notre classe (CotationType) et on fixe l'action qui traite le form
            $form = $this->createForm(
                CotationType::class,
                $cotation,
                ['attr' => [    // on rajouter des attributs... inspecter le DOM pour les voir
                    'class' => 'form-cotation', // on aura besoin pour cibler le form en JS (voir script dans la vue)
                    'data-critere' => $critere->getId() // on aura besoin quand on fera l'appel AXIOS (voir script dans la vue)
                ]]
            );


            $forms[] = $form->createView(); // normallement on renvoie uniquement l'objet $form à la vue et 
            // form_start lance createView, mais c'est uniquement possible 
            // quand on renvoie un seul formulaire
        };

        return $this->render('cotation/forms_cotation.html.twig', [
            'entreprise' => $entreprise,
            'forms' => $forms,
        ]);
    }

    #[Route('/cotation/enregistrer', name: 'enregistrer_cotation')]
    public function enregistrerCotation(
        Request $req,
        EntrepriseRepository $entRep,
        ManagerRegistry $doctrine
    ) {



        // Récupérez l'entité entreprise en fonction de l'id du POST 
        $entrepriseId = $req->request->get('entreprise');
        // dd($entrepriseId);
        $entreprise = $entRep->find($entrepriseId);

        // Récupérez le critére en fonction de l'id du POST
        $critereId = $req->request->get('critere');
        $critere = $entRep->find($critereId);

        $cotation = new Cotation();
        $form = $this->createForm(
            CotationType::class,
            $cotation
        );

        $form->handleRequest($req);

        
        // Parcourez les données du formulaire et enregistrez la cotation
        $entityManager = $doctrine->getManager();
        
        $critere = $doctrine->getRepository(Critere::class)->find($critereId);
        
        // Créez et remplissez l'entité Cotation pour chaque critère
        $cotation->setEntreprise($entreprise);
        $cotation->setCritere($critere);
        $cotation->setUser($this->getUser());
        
        // En supposant que 'note' est un champ de votre formulaire
        // Remplissez les autres champs de manière similaire
        $entityManager->persist($cotation);
        //  dd($cotation);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Cotations enregistrées avec succès']);
    }
}
