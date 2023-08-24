<?php

namespace App\Controller\Frontend;

use App\Entity\Message;
use App\Form\MessageInternauteType;
use App\Repository\MessageRepository;
use App\Service\AllRepositories;
use App\Service\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class FrontendContactController extends AbstractController
{
    public function __construct(private Utility $utility, private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_frontend_contact_toggle', methods: ['GET','POST'])]
    public function toggle(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageInternauteType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setSendAt($this->utility->fuseauGMT());

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            sweetalert()->addSuccess("Votre message a été envoyé avec succès! Nous vous repondrons incessament!");

            return $this->redirectToRoute('app_frontend_contact_toggle', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontend/contact.html.twig',[
            'message' => $message,
            'form' => $form,
        ]);
    }
}