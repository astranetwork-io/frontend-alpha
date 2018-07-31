<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\UserRole;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Access
{
    private $entityManager;
    private $tokenStorage;

    const MODULES =
        [
            'projects' => ['self', 'read', 'edit'],
            'finance' => ['self', 'read', 'edit'],
            'work' => ['self', 'read', 'edit'],
            'config' =>
                [
                    'user_read', 'user_mod',
                    'user_role_read', 'user_role_edit',
                    'matrix_read', 'matrix_mod'
                ],
        ];

    public function __construct(EntityManager $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $zone
     * @param $action
     * @param UserRole[] $userRoles
     * @return bool
     */
    public function checkAccess($zone, $action, $userRoles = null) {
        if (is_null($userRoles)) {
            $userRoles = $this->getCurrentUserRolle();
        }

        foreach ($userRoles as $role) {
            if ($role->checkAccess($zone, $action)) return true;
        }

        return false;
    }

    private function getCurrentUserRolle()
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        return $user->getRoles();
    }

}