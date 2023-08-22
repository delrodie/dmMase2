<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/referentiel')]
class FrontendReferentielController extends AbstractController
{
    #[Route('/', name: 'app_frontend_referentiel_index')]
    public function index()
    {
        return $this->render('frontend/referentiel.html.twig');
    }
}