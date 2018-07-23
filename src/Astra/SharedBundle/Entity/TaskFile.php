<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectFile
 *
 * @ORM\Table(name="task_file")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\TaskFileRepository")
 */
class TaskFile
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
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Task", inversedBy="files")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var Task
     */
    private $task;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\File")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var File
     */
    private $file;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic = false;


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
     * Set project
     *
     * @param Task $task
     *
     * @return self
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get project
     *
     * @return Task
     */
    public function getProject()
    {
        return $this->task;
    }

    /**
     * Set file
     *
     * @param File $file
     *
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     * @return self
     */
    public function setIsPublic(bool $isPublic)
    {
        $this->isPublic = $isPublic;
        return $this;
    }



    /**
     * Get isPublic
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Get task
     *
     * @return \Astra\SharedBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }
}
