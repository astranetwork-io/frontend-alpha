<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\UserRole;
use Doctrine\ORM\EntityManager;

class UserRoleService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveUserRolle(UserRole $userRolle, User $currentUser = null)
    {
        $this->entityManager->persist($userRolle);
        $this->entityManager->flush();
    }

    public function removeUserRolle(UserRole $userRolle, User $currentUser = null)
    {
        $this->entityManager->remove($userRolle);
        $this->entityManager->flush();
    }
}