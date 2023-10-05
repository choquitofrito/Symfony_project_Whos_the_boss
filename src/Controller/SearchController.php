<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route("/entreprises", name:"entreprises")]

    public function search(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Entreprise::class);

        $emplacement = $request->query->get('emplacement');
        $secteur = $request->query->get('secteur');
        $cotation = $request->query->get('cotation');

        $queryBuilder = $repository->createQueryBuilder('tri');

        if ($emplacement) {
            $queryBuilder->andWhere('tri.emplacement = :emplacement');
            $queryBuilder->setParameter('emplacement', $emplacement);
        }

        if ($secteur) {
            $queryBuilder->andWhere('tri.secteur = :secteur');
            $queryBuilder->setParameter('secteur', $secteur);
        }

        if ($cotation) {
            $queryBuilder->andWhere('tri.cotation = :cotation');
            $queryBuilder->setParameter('cotation', $cotation);
        }

        // Tri par défaut (par exemple, tri par emplacement ascendant)
        $queryBuilder->orderBy('tri.emplacement', 'ASC');
        $results = $queryBuilder->getQuery()->getResult();

        // Renvoyer les résultats à la vue
        return $this->render('search/entreprises.html.twig', [
            'results' => $results,
        ]);
    }


    // public function triResultats (ManagerRegistry $doctrine){
            
    //     $em = $doctrine->getManager();
    //     $query = $em->createQuery("SELECT l, e, em
    //                                 FROM App\Entity\Livre l
    //                                 LEFT JOIN l.exemplaires e
    //                                 LEFT JOIN e.emprunts em");

    //     $res= $query->getResult();
    //     dd($res);
    // }

    }