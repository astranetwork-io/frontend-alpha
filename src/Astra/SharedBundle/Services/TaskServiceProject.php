<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\TaskList;
use Doctrine\ORM\EntityManager;

class TaskServiceProject
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Project $project
     * @return TaskList
     */
    public function getActiveTaskList(Project $project)
    {
        $taskList = $project->getTaskList()->last();
        if($taskList)return $taskList;
        $taskList = new TaskList();
        $taskList->setProject($project);
        $this->entityManager->persist($taskList);
        $this->entityManager->flush($taskList);
        return $taskList;
    }
}