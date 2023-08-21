<?php

namespace App\Controller\Backend;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/mission')]
class BackendMissionController extends AbstractController
{
    public function __construct(
        private Utility $utility,
        private GestionMedia $gestionMedia,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_mission_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): Response
    {
        $mission = $this->allRepositories->allCache('mission', true);
        if ($mission)
            return $this->redirectToRoute('app_backend_mission_edit',['id'=>$mission->getId()], Response::HTTP_SEE_OTHER);
        else
            return $this->redirectToRoute('app_backend_mission_new',[], Response::HTTP_SEE_OTHER);

    }

    #[Route('/new', name: 'app_backend_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias et slug
            $this->gestionMedia->media($form, $mission, 'presentation');
            $this->utility->slug($mission);
            $mission->setUpdatedAt($this->utility->fuseauGMT());

            $entityManager->persist($mission);
            $entityManager->flush();

            sweetalert()->addSuccess("La mission a été enregistrée avec succès!", "Enregistrement");

            return $this->redirectToRoute('app_backend_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('backend_mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias et slug
            $this->gestionMedia->media($form, $mission, 'presentation');
            $this->utility->slug($mission);
            $mission->setUpdatedAt($this->utility->fuseauGMT());

            $entityManager->flush();

            sweetalert()->addSuccess("La mission a été modifiée avec succès!", "Modification");

            return $this->redirectToRoute('app_backend_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_mission_delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mission);
            $entityManager->flush();

            if ($mission->getMedia())
                $this->gestionMedia->removeUpload($mission->getMedia(), 'presentation');

            sweetalert()->addSuccess("La mission a été supprimée avec succès!");
        }

        return $this->redirectToRoute('app_backend_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
