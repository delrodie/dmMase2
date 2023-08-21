<?php

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mission')]
class FrontendMissionController extends AbstractController
{
    public function __construct(private AllRepositories $allRepositories)
    {
    }

    #[Route('/', name: 'app_frontend_mission_page')]
    public function page()
    {
        return $this->render("frontend/mission.html.twig",[
            'mission' => $this->allRepositories->allCache('mission')
        ]);
    }
}