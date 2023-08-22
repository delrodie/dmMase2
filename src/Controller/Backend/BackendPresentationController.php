<?php

namespace App\Controller\Backend;

use App\Entity\Presentation;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/presentation')]
class BackendPresentationController extends AbstractController
{
    public function __construct(
        private Utility $utility,
        private GestionMedia $gestionMedia,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_presentation_index', methods: ['GET'])]
    public function index(PresentationRepository $presentationRepository): Response
    {
        $presentation  = $this->allRepositories->allCache('presentation', true);
        if (!$presentation)
            return $this->redirectToRoute('app_backend_presentation_new',[], Response::HTTP_SEE_OTHER);
        else
            return $this->redirectToRoute('app_backend_presentation_edit',['id'=>$presentation->getId()]);

    }

    #[Route('/new', name: 'app_backend_presentation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $presentation = new Presentation();
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias et slug
            $this->gestionMedia->media($form, $presentation, 'presentation');
            $this->utility->slug($presentation);
            $presentation->setUpdatedAt($this->utility->fuseauGMT());

            $entityManager->persist($presentation);
            $entityManager->flush();

            sweetalert()->addSuccess("La presentation a été ajoutée avec succès!", "Enregistrement");

            return $this->redirectToRoute('app_backend_presentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_presentation/new.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_presentation_show', methods: ['GET'])]
    public function show(Presentation $presentation): Response
    {
        return $this->render('backend_presentation/show.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_presentation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentation $presentation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias et slug
            $this->gestionMedia->media($form, $presentation, 'presentation');
            $this->utility->slug($presentation);
            $presentation->setUpdatedAt($this->utility->fuseauGMT());

            $entityManager->flush();

            sweetalert()->addSuccess("La presentation a été modifiée avec succès!", "Modification");

            return $this->redirectToRoute('app_backend_presentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_presentation/edit.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_presentation_delete', methods: ['POST'])]
    public function delete(Request $request, Presentation $presentation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presentation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($presentation);
            $entityManager->flush();

            if ($presentation->getMedia())
                $this->gestionMedia->removeUpload($presentation->getMedia(), 'presentation');

            sweetalert()->addSuccess("La présentation a été supprimée avec succès!", 'Supression');
        }

        return $this->redirectToRoute('app_backend_presentation_index', [], Response::HTTP_SEE_OTHER);
    }
}
