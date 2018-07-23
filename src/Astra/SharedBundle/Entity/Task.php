<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\TaskRepository")
 */
class Task
{
    const STATUS_TODO = 'todo';
    const STATUS_PROCESS = 'process';
    const STATUS_COMPLEET = 'compleet';

    /**
     * @var array
     * Номера - порядок вывода в Agile
     */
    public static $fullStatusList =
        [
            self::STATUS_TODO => 0,
            self::STATUS_PROCESS => 1,
            self::STATUS_COMPLEET => 2,
        ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=255)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_work", type="datetime", nullable=false)
     */
    private $startWork;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_work", type="datetime", nullable=false)
     */
    private $endWork;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    private $status;


    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User", inversedBy="createdTasks")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var User
     */
    private $author;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User", inversedBy="workTasks")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var User
     */
    private $worker;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\TaskListItem", mappedBy="task")
     * @var TaskListItem[]
     */
    private $taskListItem = [];

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\TaskFile", mappedBy="task")
     * @var TaskFile[]
     */
    private $files = [];

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_calendar", type="boolean", nullable=false)
     */
    private $isCalendar = false;

    public function __construct()
    {
        $this->status = self::STATUS_TODO;
        $this->created = new \DateTime();
        $this->startWork = new \DateTime();
        $this->endWork = new \DateTime();
        $this->endWork->modify('+1 day');
        $this->taskListItem = new ArrayCollection();
        $this->files = new ArrayCollection();
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
     * Set caption
     *
     * @param string $caption
     *
     * @return Task
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Task
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
     * Set status
     *
     * @param integer $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set author
     *
     * @param \Astra\SharedBundle\Entity\User $author
     *
     * @return Task
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Astra\SharedBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set worker
     *
     * @param \Astra\SharedBundle\Entity\User $worker
     *
     * @return Task
     */
    public function setWorker(User $worker = null)
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * Get worker
     *
     * @return \Astra\SharedBundle\Entity\User
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Set startWork
     *
     * @param \DateTime $startWork
     *
     * @return Task
     */
    public function setStartWork($startWork)
    {
        $this->startWork = $startWork;

        return $this;
    }

    /**
     * Get startWork
     *
     * @return \DateTime
     */
    public function getStartWork()
    {
        return $this->startWork;
    }

    /**
     * Set endWork
     *
     * @param \DateTime $endWork
     *
     * @return Task
     */
    public function setEndWork($endWork)
    {
        $this->endWork = $endWork;

        return $this;
    }

    /**
     * Get endWork
     *
     * @return \DateTime
     */
    public function getEndWork()
    {
        return $this->endWork;
    }



    /**
     * Add taskListItem
     *
     * @param \Astra\SharedBundle\Entity\TaskListItem $taskListItem
     *
     * @return Task
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaskListItem()
    {
        return $this->taskListItem;
    }

    /**
     * Add file
     *
     * @param TaskFile $file
     *
     * @return self
     */
    public function addFile(TaskFile $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param TaskFile $file
     */
    public function removeFile(TaskFile $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return TaskFile[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set isCalendar
     *
     * @param boolean $isCalendar
     *
     * @return Task
     */
    public function setIsCalendar($isCalendar)
    {
        $this->isCalendar = $isCalendar;

        return $this;
    }

    /**
     * Get isCalendar
     *
     * @return boolean
     */
    public function getIsCalendar()
    {
        return $this->isCalendar;
    }
}
