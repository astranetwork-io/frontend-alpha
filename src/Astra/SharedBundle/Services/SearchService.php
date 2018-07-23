<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\Wallet;
use Astra\SharedBundle\Entity\WalletTransaction;
use Astra\SharedBundle\Entity\WalletTransactionItem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SearchService
{
    private $entityManager;
    private $tokenStorage;
    private $currentUser;

    public function __construct(EntityManager $entityManager, TokenStorageInterface $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->currentUser = $this->tokenStorage->getToken()->getUser();
    }

    public function SearchUsers(string $request, $limit = 0, $filtedUsers = [])
    {

        $result = $this->entityManager->getRepository('AstraSharedBundle:User')->findByWord($request,$limit,$filtedUsers);
        foreach ($result as $key=>$value)
        {
            if ($this->currentUser===$value)
            {
                unset($result[$key]);
            }
        }
        return $result;
    }
}