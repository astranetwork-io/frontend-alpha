<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskListItem
 *
 * @ORM\Table(name="task_list_item")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\TaskListItemRepository")
 */
class TaskListItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\TaskList", inversedBy="taskListItem")
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="CASCADE")
     * @var TaskList
     */
    private $taskList;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Task", inversedBy="taskListItem")
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="CASCADE")
     * @var Task
     */
    private $task;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set taskList
     *
     * @param TaskList $taskList
     *
     * @return TaskListItem
     */
    public function setTaskList($taskList)
    {
        $this->taskList = $taskList;

        return $this;
    }

    /**
     * Get taskList
     *
     * @return TaskList
     */
    public function getTaskList()
    {
        return $this->taskList;
    }

    /**
     * Set task
     *
     * @param Task $task
     *
     * @return TaskListItem
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return TaskListItem
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
