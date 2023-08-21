<?php

namespace App\Service;

use App\Repository\ActualiteRepository;
use App\Repository\MissionRepository;
use App\Repository\SliderRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AllRepositories
{
    public function __construct(
        private CacheInterface $cache,
        private SliderRepository $sliderRepository,
        private MissionRepository $missionRepository,
        private ActualiteRepository $actualiteRepository
    )
    {
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
            default => false,
        };
    }
}