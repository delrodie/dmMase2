<?php

namespace App\Controller;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('frontend/home.html.twig',[
            'slides' => $this->allRepositories->allCache('slides')
        ]);
    }
}
