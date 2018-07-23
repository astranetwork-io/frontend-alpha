<?php
namespace Astra\SharedBundle\Services\view;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserService
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, TokenStorageInterface $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function getCurrentUser()
    {
        return $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
    }

    public function userPost(User $user = null)
    {
        if (!$user) $user = $this->getCurrentUser();
        if (!$user) return '';

        return '';
    }
}