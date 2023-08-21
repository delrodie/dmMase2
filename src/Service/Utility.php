<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Utility
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function slug(object $entity): void
    {
        $entity->setSlug((new AsciiSlugger())->slug(strtolower($entity->getTitre())));
    }

    public function fuseauGMT(): \DateTime
    {
        // Définissons l'heure actuelle en utilisant le fuseau horaire GMT
        $fuseauGMT = new \DateTimeZone('GMT');
        return (new \DateTime('now', $fuseauGMT));
    }
}