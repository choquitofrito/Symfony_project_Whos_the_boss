<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Entreprise;

class SearchController extends AbstractController
{
    #[Route("/entreprises", name:"entreprises")]

    public function search(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
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
}