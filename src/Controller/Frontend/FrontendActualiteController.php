<?php

namespace App\Controller\Frontend;

use App\Entity\Actualite;
use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actualites')]
class FrontendActualiteController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name:"app_frontend_actualite_index")]
    public function index()
    {
        return $this->render('frontend/actualites.html.twig',[
            'actualites' => $this->allRepositories->allCache('actualites')
        ]);
    }

    #[Route('/{slug}', name: 'app_frontend_actualite_show', methods: ['GET'])]
    public function show(Actualite $actualite)
    {
        return $this->render('frontend/actualite.html.twig',[
            'actualite' => $actualite
        ]);
    }

    #[Route('/liste/1', name: 'app_frontend_actualite_liste')]
    public function liste()
    {
       return $this->render('frontend/actualite_liste.html.twig',[
           'actualites' => $this->allRepositories->allCache('actualites')
       ]);
    }
}