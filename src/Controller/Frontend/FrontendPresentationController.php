<?php

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/qui-sommes-nous')]
class FrontendPresentationController extends AbstractController
{
    public function __construct(private AllRepositories $allRepositories)
    {
    }

    #[Route('/{slug}', name: 'app_frontend_presentation_index')]
    public function index($slug)
    {
        return match ($slug){
            'presentation' => $this->render('frontend/presentation.html.twig',[
                'presentation' => $this->allRepositories->allCache('presentation')
            ]),
            'conseil-administration' => $this->render('frontend/portrait.html.twig',[
                'portraits' => $this->allRepositories->allCache('administration'),
                'titre' => "Conseil d'administration"
            ]),
            'comite-de-pilotage' => $this->render('frontend/portrait.html.twig',[
                'portraits' => $this->allRepositories->allCache('comite'),
                'titre' => "Comité de pilotage"
            ]),
            default => $this->redirectToRoute('app_home'),
        };
    }


}