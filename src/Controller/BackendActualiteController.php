<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/actualite')]
class BackendActualiteController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private Utility $utility,
        private GestionMedia $gestionMedia
    )
    {
    }

    #[Route('/', name: 'app_backend_actualite_index', methods: ['GET'])]
    public function index(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('backend_actualite/index.html.twig', [
            'actualites' => $this->allRepositories->allCache('actualites', true),
        ]);
    }

    #[Route('/new', name: 'app_backend_actualite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias et slug
            $this->gestionMedia->media($form, $actualite, 'actualite');
            $this->utility->slug($actualite);
            $actualite->setUpdatedAt($this->utility->fuseauGMT());

            $entityManager->persist($actualite);
            $entityManager->flush();

            sweetalert()->addSuccess("{$actualite->getTitre()} a été ajoutée avec succès!", "Enregistrement");

            return $this->redirectToRoute('app_backend_actualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_actualite/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_actualite_show', methods: ['GET'])]
    public function show(Actualite $actualite): Response
    {
        return $this->render('backend_actualite/show.html.twig', [
            'actualite' => $actualite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_actualite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actualite $actualite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $actualite, 'actualite');
            $this->utility->slug($actualite);
            $actualite->setUpdatedAt($this->utility->fuseauGMT());

            $entityManager->flush();

            sweetalert()->addSuccess("L'actualité a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_actualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_actualite/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_actualite_delete', methods: ['POST'])]
    public function delete(Request $request, Actualite $actualite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($actualite);
            $entityManager->flush();

            if ($actualite->getMedia())
                $this->gestionMedia->removeUpload($actualite->getMedia(), 'actualite');

            sweetalert()->addSuccess("L'actualité a été supprimée avec succès!");
        }

        return $this->redirectToRoute('app_backend_actualite_index', [], Response::HTTP_SEE_OTHER);
    }
}
