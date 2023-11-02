<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Utility
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
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

    public function getUsers(string $username): array
    {
        $getUsers = $this->userRepository->findWithout($username);
        $users = [];
        foreach ($getUsers as $getUser){
            $roles = $getUser->getRoles()[0] ?? $getUser->getRoles();
            switch ($roles) {
                case 'ROLE_ADMIN' :
                    $role = 'Administrateur';
                    break;
                case 'ROLE_GERANT':
                    $role = 'Gérant';
                    break;
                case 'ROLE_CAISSE':
                    $role = 'Caisse';
                    break;
                default:
                    $role = 'Utilisateur';
                    break;
            }
            $users[] = [
                'id' => $getUser->getId(),
                'userIdentifier' => $getUser->getUserIdentifier(),
                'role' => $role,
                'connexion' => $getUser->getConnexion(),
                'lastConnectedAt' => $getUser->getLastConnectedAt()
            ];
        };

        return $users;
    }
}