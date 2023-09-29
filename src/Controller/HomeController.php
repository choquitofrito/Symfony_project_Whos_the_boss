<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController{
    
    #[Route('/', name:'home')]
    public function home (){
        return $this->render ('home/accueil.html.twig');
    }


    #[Route('/entreprises', name:'entreprises')]
    public function entreprises (): Response {
        return $this->render ('home/entreprises.html.twig');
    }

    #[Route('/classement', name:'classement')]
    public function classement (): Response {
        return $this->render ('home/classement.html.twig');
    }

    #[Route('/vue3', name:'vue3')]
    public function vue3 (): Response {
        return $this->render ('home/vue3.html.twig');
    }
}