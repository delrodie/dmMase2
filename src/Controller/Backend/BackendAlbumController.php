<?php

namespace App\Controller\Backend;

use App\Entity\Album;
use App\Entity\Photo;
use App\Form\AlbumType;
use App\Form\PhotoType;
use App\Repository\AlbumRepository;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/album')]
class BackendAlbumController extends AbstractController
{
    public function __construct(
        private GestionMedia $gestionMedia, private Utility $utility,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_album_index', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('backend_album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_album_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion des médias et des slug
            $this->gestionMedia->media($form, $album, 'galerie');
            $this->utility->slug($album);

            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_album_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/ajouter/photo', name: 'app_backend_album_show', methods: ['GET','POST'])]
    public function show(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $medias = $form->get('media')->getData();

            $nombre = 0;
            foreach ($medias as $mediaFile){
                $photo = new Photo();
                $media = $this->gestionMedia->upload($mediaFile, 'galerie');
                $photo->setMedia($media);
                $photo->setAlbum($album);
                $entityManager->persist($photo);
                $nombre++;
            }

            sweetalert("{$nombre} photo(s) ont été ajoutées à l'album");

            $entityManager->flush();

            return $this->redirectToRoute('app_backend_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_album/show.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_album_delete', methods: ['POST'])]
    public function delete(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager->remove($album);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_album_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/supprimer/photo', name: 'app_backend_album_suppression_photo', methods: ['GET'])]
    public function supprimerPhoto(Photo $photo, EntityManagerInterface $entityManager): Response
    {
        $id = $photo->getAlbum()->getId();

        $entityManager->remove($photo);
        $this->gestionMedia->removeUpload($photo->getMedia(), 'galerie');
        $entityManager->flush();

        sweetalert('La photo a été supprimée avec succès!');

        return $this->redirectToRoute('app_backend_album_edit',['id' => $id]);
    }
}
