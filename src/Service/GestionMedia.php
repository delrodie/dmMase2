<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GestionMedia
{
    private $mediaSlide;
    private $mediaPresentation;
    private $mediaActualite;
    private $mediaEntreprise;

    private $mediaGalerie;

    public function __construct(
        $slideDirectory, $presentationDirectory, $actualiteDirectory, $entrepriseDirectory,
        $galerieDirectory
    )
    {
        $this->mediaSlide = $slideDirectory;
        $this->mediaPresentation = $presentationDirectory;
        $this->mediaActualite = $actualiteDirectory;
        $this->mediaEntreprise = $entrepriseDirectory;
        $this->mediGalerie = $galerieDirectory;
    }

    /**
     * @param $form
     * @param object $entity
     * @param string $entityName
     * @return void
     */
    public function media($form, object $entity, string $entityName): void
    {
        // Gestion des médias
        $mediaFile = $form->get('media')->getData();
        if ($mediaFile){
            $media = $this->upload($mediaFile, $entityName);

            if ($entity->getMedia()){
                $this->removeUpload($entity->getMedia(), $entityName);
            }

            $entity->setMedia($media);
        }
    }


    /**
     * @param UploadedFile $file
     * @param $media
     * @return string
     */
    public function upload(UploadedFile $file, $media = null): string
    {
        // Initialisation du slug
        $slugify = new AsciiSlugger();

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slug($originalFileName);
        $newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension();

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'slide') $file->move($this->mediaSlide, $newFilename);
            elseif ($media === 'presentation') $file->move($this->mediaPresentation, $newFilename);
            elseif ($media === 'actualite') $file->move($this->mediaActualite, $newFilename);
            elseif ($media === 'adherant') $file->move($this->mediaEntreprise, $newFilename);
            elseif ($media === 'galerie') $file->move($this->mediGalerie, $newFilename);
            else $file->move($this->mediaPresentation, $newFilename);
        }catch (FileException $e){

        }

        return $newFilename;
    }

    /**
     * Suppression de l'ancien media sur le server
     *
     * @param $ancienMedia
     * @param null $media
     * @return bool
     */
    public function removeUpload($ancienMedia, $media = null): bool
    {
        if ($media === 'slide') unlink($this->mediaSlide.'/'.$ancienMedia);
        elseif ($media === 'presentation') unlink($this->mediaPresentation.'/'.$ancienMedia);
        elseif ($media === 'actualite') unlink($this->mediaActualite.'/'.$ancienMedia);
        elseif ($media === 'adherant') unlink($this->mediaEntreprise.'/'.$ancienMedia);
        elseif ($media === 'galerie') unlink($this->mediGalerie.'/'.$ancienMedia);
        else return false;

        return true;
    }

}