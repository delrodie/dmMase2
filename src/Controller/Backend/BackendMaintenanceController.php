<?php

namespace App\Controller\Backend;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Repository\MaintenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/maintenance')]
class BackendMaintenanceController extends AbstractController
{
    #[Route('/', name: 'app_backend_maintenance_toggle')]
    public function toggle(Request $request, MaintenanceRepository $maintenanceRepository): Response
    {
        $maintenance = $maintenanceRepository->findOneBy([],['id'=>"DESC"]);
        if ($maintenance) {
            return $this->redirectToRoute('app_backend_maintenance_edit', ['id' => $maintenance->getId()]);
        }

        // Activation de la maintenance
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $maintenanceRepository->save($maintenance, true);

            sweetalert()->addSuccess("L'action sur la maintenance a été effectuée avec succès!");

            return $this->redirectToRoute('app_backend_maintenance_edit',['id'=>$maintenance->getId()]);
        }

        return $this->render('backend/maintenance.html.twig',[
            'maintenance' => $maintenance,
            'form' => $form
        ]);

    }

    #[Route('/{id}', name: 'app_backend_maintenance_edit',methods: ['GET','POST'])]
    public function edit(Request $request, Maintenance $maintenance, MaintenanceRepository $maintenanceRepository): Response
    {
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $maintenanceRepository->save($maintenance, true);

            sweetalert()->addSuccess("La maintenance a été modifiée avec succès!");
        }

        return $this->render('backend/maintenance.html.twig',[
            'maintenance' => $maintenance,
            'form' => $form
        ]);
    }
}