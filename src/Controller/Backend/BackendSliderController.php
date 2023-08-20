<?php

namespace App\Controller\Backend;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use App\Service\GestionMedia;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/slider')]
class BackendSliderController extends AbstractController
{
    public function __construct(
        private GestionMedia $gestionMedia, private Utility $utility
    )
    {
    }

    #[Route('/', name: 'app_backend_slider_index', methods: ['GET'])]
    public function index(SliderRepository $sliderRepository): Response
    {
        return $this->render('backend_slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_slider_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion des médias et des slug
            $this->gestionMedia->media($form, $slider, 'slide');
            $this->utility->slug($slider);

            $entityManager->persist($slider);
            $entityManager->flush();

            sweetalert()->addSuccess("Le slug a été enregistré avec succès!");

            return $this->redirectToRoute('app_backend_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_slider_show', methods: ['GET'])]
    public function show(Slider $slider): Response
    {
        return $this->render('backend_slider/show.html.twig', [
            'slider' => $slider,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_slider_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slider $slider, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion des medias et slug
            $this->gestionMedia->media($form,$slider, 'slide');
            $this->utility->slug($slider);

            $entityManager->flush();

            sweetalert()->addSuccess("Le slide a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_slider_delete', methods: ['POST'])]
    public function delete(Request $request, Slider $slider, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $entityManager->remove($slider);
            $entityManager->flush();

            if ($slider->getMedia())
                $this->gestionMedia->removeUpload($slider->getMedia(), 'slide');

            sweetalert()->addSuccess("Le slide {$slider->getTitre()} a été supprimé avec succès!", 'SUPPRESSION');
        }

        return $this->redirectToRoute('app_backend_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
