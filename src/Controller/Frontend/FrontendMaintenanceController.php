<?php

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dev')]
class FrontendMaintenanceController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_maintenance')]
    public function index(): Response
    {
        return $this->render('frontend/home.html.twig',[
            'slides' => $this->allRepositories->allCache('slides'),
            'mission' => $this->allRepositories->allCache('mission'),
            'actualites' => $this->allRepositories->allCache('actualites'),
            'entreprises' => $this->allRepositories->getEntrepriseAleatoire()
        ]);
    }
}