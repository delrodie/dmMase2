<?php

namespace App\Controller\Backend;

use App\Entity\Portrait;
use App\Form\PortraitType;
use App\Repository\PortraitRepository;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/backend/portrait')]
class BackendPortraitController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private Utility $utility,
        private GestionMedia $gestionMedia
    )
    {
    }

    #[Route('/', name: 'app_backend_portrait_index', methods: ['GET'])]
    public function index(PortraitRepository $portraitRepository): Response
    {
        return $this->render('backend_portrait/index.html.twig', [
            'portraits' => $portraitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_portrait_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $portrait = new Portrait();
        $form = $this->createForm(PortraitType::class, $portrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $portrait, 'presentation');
            $portrait->setSlug((new AsciiSlugger())->slug(strtolower($portrait->getNom().'-'.$portrait->getPrenoms())));

            $entityManager->persist($portrait);
            $entityManager->flush();

            sweetalert()->addSuccess("{$portrait->getNom()} a été ajouté avec succès!");

            return $this->redirectToRoute('app_backend_portrait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_portrait/new.html.twig', [
            'portrait' => $portrait,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_portrait_show', methods: ['GET'])]
    public function show(Portrait $portrait): Response
    {
        return $this->render('backend_portrait/show.html.twig', [
            'portrait' => $portrait,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_portrait_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Portrait $portrait, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PortraitType::class, $portrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $portrait, 'presentation');
            $portrait->setSlug((new AsciiSlugger())->slug(strtolower($portrait->getNom().'-'.$portrait->getPrenoms())));

            $entityManager->flush();

            sweetalert()->addSuccess("{$portrait->getNom()} a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_portrait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_portrait/edit.html.twig', [
            'portrait' => $portrait,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_portrait_delete', methods: ['POST'])]
    public function delete(Request $request, Portrait $portrait, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portrait->getId(), $request->request->get('_token'))) {
            $entityManager->remove($portrait);
            $entityManager->flush();

            if($portrait->getMedia())
                $this->gestionMedia->removeUpload($portrait->getMedia(), 'presentation');
        }

        return $this->redirectToRoute('app_backend_portrait_index', [], Response::HTTP_SEE_OTHER);
    }
}
