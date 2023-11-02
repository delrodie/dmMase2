<?php

namespace App\Controller\Frontend;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/galerie')]
class FrontendGalerieController extends AbstractController
{
    public function __construct(
        private AlbumRepository $albumRepository,
        private PhotoRepository $photoRepository
    )
    {
    }

    #[Route('/', name: 'app_frontend_galerie')]
    public function index()
    {
        return $this->render('frontend/galerie.html.twig',[
            'albums' => $this->albumRepository->findBy([],['id' =>"DESC"])
        ]);
    }

    #[Route('/{slug}', name: 'app_frontend_galerie_album', methods: ['GET'])]
    public function album(Album $album)
    {
        return $this->render('frontend/galerie_album.html.twig',[
            'album' => $album
        ]);
    }
}