<?php

namespace App\Controller\Backend;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/adhesion')]
class BackendAdhesionController extends AbstractController
{
    #[Route('/', name: 'app_backend_adhesion_index')]
    public function index(EntrepriseRepository $entrepriseRepository)
    {
        return $this->render('backend/entreprises.html.twig',[
            'entreprises' => $entrepriseRepository->findBy([],['id'=>"DESC"])
        ]);
    }
}