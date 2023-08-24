<?php

namespace App\Service;

use App\Repository\ActualiteRepository;
use App\Repository\ContactRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\MaintenanceRepository;
use App\Repository\MissionRepository;
use App\Repository\PortraitRepository;
use App\Repository\PresentationRepository;
use App\Repository\SliderRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AllRepositories
{
    public function __construct(
        private CacheInterface $cache,
        private SliderRepository $sliderRepository,
        private MissionRepository $missionRepository,
        private ActualiteRepository $actualiteRepository,
        private PresentationRepository $presentationRepository,
        private PortraitRepository $portraitRepository,
        private EntrepriseRepository $entrepriseRepository,
        private MaintenanceRepository $maintenanceRepository,
        private ContactRepository $contactRepository
    )
    {
    }

    public function isMaintenance(): bool
    {
        $maintenance = $this->maintenanceRepository->findOneBy(['statut'=>true]) ;
        if ($maintenance) return true;

        return false;
    }

    public function getEntrepriseAleatoire()
    {
        $entreprises = $this->allCache('entreprise');

        shuffle($entreprises);

        return array_slice($entreprises, 0,count($entreprises));
    }

    public function allCache(string $cacheName, bool $delete = false)
    {
        if ($delete) $this->cache->delete($cacheName);

        return $this->cache->get($cacheName, function (ItemInterface $item) use($cacheName){
            $item->expiresAfter(86400); // Une semaine
            return $this->repositories($cacheName);
        });
    }

    public function repositories(string $cacheName)
    {
        return match ($cacheName) {
            'slides' => $this->sliderRepository->findBy([],['id'=>"DESC"]),
            'mission' => $this->missionRepository->findOneBy([],['id'=>"DESC"]),
            'actualites' => $this->actualiteRepository->findBy([],['id'=>"DESC"]),
            'presentation' => $this->presentationRepository->findOneBy([],['id'=>"DESC"]),
            'administration' => $this->portraitRepository->findByInstance('conseil'),
            'comite' => $this->portraitRepository->findByInstance('pilotage'),
            'entreprise' => $this->entrepriseRepository->findAll(),
            'contact' => $this->contactRepository->findOneBy([],['id'=>"DESC"]),
            default => [],
        };
    }
}