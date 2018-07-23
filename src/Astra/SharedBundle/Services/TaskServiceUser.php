<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\TaskList;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class TaskServiceUser
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return TaskList
     */
    public function getActiveTaskList(User $user)
    {
        $taskList = $user->getTaskList()->last();
        if($taskList)return $taskList;
        $taskList = new TaskList();
        $taskList->setUser($user);
        $this->entityManager->persist($taskList);
        return $taskList;
    }


}