<?php

namespace App\Controller\Frontend;

use App\Entity\Entreprise;
use App\Form\AdhesionType;
use App\Repository\EntrepriseRepository;
use App\Service\GestionMedia;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/adhesion')]
class FrontendAdhesionController extends AbstractController
{
    public function __construct(
        private GestionMedia $gestionMedia,
        private Utility $utility,
        private EntrepriseRepository $entrepriseRepository
    )
    {
    }

    #[Route('/', name: 'app_frontend_adhesion_index')]
    public function index()
    {
        return $this->render('frontend/adhesion.html.twig');
    }

    #[Route('/{slug}', name: 'app_frontend_adhesion_entreprise',methods: ['GET','POST'])]
    public function entreprise(Request $request, $slug): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(AdhesionType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $dataSlug = (new AsciiSlugger())->slug($entreprise->getRaisonSociale());
            $findEntreprise = $this->entrepriseRepository->findOneBy(['slug' => $dataSlug]);
            if ($findEntreprise){
                sweetalert()->addError("Echec! Votre entreprise a déjà été ajoutée dans la plateforme!");
                return $this->render('frontend/adhesion_form.html.twig',[
                    'adherant' => ucwords(str_replace('-', ' ', $slug)),
                    'entreprise' => $entreprise,
                    'form' => $form
                ]);
            }

            $entreprise->setSlug($dataSlug);

            // Gestion des médias
            $mediaFile = $form->get('logo')->getData();
            if ($mediaFile){
                $entreprise->setLogo($this->gestionMedia->upload($mediaFile, 'adherant'));
            }

            $entreprise->setType($this->type($slug));

            $this->entrepriseRepository->save($entreprise, true);

            sweetalert()->addSuccess("Félicitations! Votre formulaire d'identification a été envoyé avec succès! Veuillez télécharger les documents et les domposer pour la finalisation de votre adhésion");

            return $this->redirectToRoute('app_frontend_adhesion_document',[
                'slug' => $entreprise->getSlug(),
                'type' => $slug
            ], Response::HTTP_SEE_OTHER);

        }

        return $this->render('frontend/adhesion_form.html.twig',[
            'adherant' => ucwords(str_replace('-', ' ', $slug)),
            'entreprise' => $entreprise,
            'form' => $form
        ]);
    }

    #[Route('/{type}/{slug}', name: 'app_frontend_adhesion_document', methods: ['GET'])]
    public function document($slug, $type): Response
    {
        $entreprise = $this->entrepriseRepository->findOneBy(['slug' => $slug]);

        if (!$entreprise){
            sweetalert()->addError("L'entreprise selectionnée n'existe pas dans la plateforme");
            return $this->redirectToRoute('app_frontend_adhesion_entreprise',['slug'=>$type], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontend/adhesion_document.html.twig',[
            'entreprise' => $entreprise,
            'type' => $this->type($type),
            'adherant' => ucwords(str_replace('-',' ', $type))
        ]);
    }

    private function type($slug): string
    {
        return match ($slug){
            'entreprises-intervenantes' => 'EI',
            default => 'EU',
        };
    }


}