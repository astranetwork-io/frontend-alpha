<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TaskList
 *
 * @ORM\Table(name="task_list")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\TaskListRepository")
 */
class TaskList
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
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Project", inversedBy="taskList")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var Project
     */
    private $project;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User", inversedBy="taskList")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\TaskListItem", mappedBy="taskList")
     * @var TaskListItem[]
     */
    private $taskListItem = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->taskListItem = new ArrayCollection();
        $this->name = 'auto_'.$this->created->format('Ymd');
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return TaskList
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TaskList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return TaskList
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project
     *
     * @param \Astra\SharedBundle\Entity\Project $project
     *
     * @return TaskList
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Astra\SharedBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add taskListItem
     *
     * @param \Astra\SharedBundle\Entity\TaskListItem $taskListItem
     *
     * @return TaskList
     */
    public function addTaskListItem(TaskListItem $taskListItem)
    {
        $this->taskListItem[] = $taskListItem;

        return $this;
    }

    /**
     * Remove taskListItem
     *
     * @param \Astra\SharedBundle\Entity\TaskListItem $taskListItem
     */
    public function removeTaskListItem(TaskListItem $taskListItem)
    {
        $this->taskListItem->removeElement($taskListItem);
    }

    /**
     * Get taskListItem
     *
     * @return \Doctrine\Common\Collections\Collection | TaskListItem[]
     */
    public function getTaskListItem()
    {
        return $this->taskListItem;
    }
}
