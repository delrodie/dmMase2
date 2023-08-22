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
            default => false,
        };
    }


}