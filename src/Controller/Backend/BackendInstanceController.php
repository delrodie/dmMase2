<?php

namespace App\Controller\Backend;

use App\Entity\Instance;
use App\Form\InstanceType;
use App\Repository\InstanceRepository;
use App\Service\AllRepositories;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/instance')]
class BackendInstanceController extends AbstractController
{
    public function __construct(private AllRepositories $allRepositories, private Utility $utility)
    {
    }

    #[Route('/', name: 'app_backend_instance_index', methods: ['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, InstanceRepository $instanceRepository): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Gestion des slugs
            $this->utility->slug($instance);

            $entityManager->persist($instance);
            $entityManager->flush();

            sweetalert()->addSuccess("L'instance a été ajoutée avec succès!");

            return $this->redirectToRoute('app_backend_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_instance/index.html.twig', [
            'instances' => $instanceRepository->findAll(),
            'instance' => $instance,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_instance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($instance);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_instance/new.html.twig', [
            'instance' => $instance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_instance_show', methods: ['GET'])]
    public function show(Instance $instance): Response
    {
        return $this->render('backend_instance/show.html.twig', [
            'instance' => $instance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_instance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Instance $instance, EntityManagerInterface $entityManager, InstanceRepository $instanceRepository): Response
    {
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Gestion des slugs
            $this->utility->slug($instance);

            $entityManager->flush();

            sweetalert()->addSuccess("L'instance a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_instance/edit.html.twig', [
            'instances' => $instanceRepository->findAll(),
            'instance' => $instance,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_instance_delete', methods: ['POST'])]
    public function delete(Request $request, Instance $instance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($instance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_instance_index', [], Response::HTTP_SEE_OTHER);
    }
}
