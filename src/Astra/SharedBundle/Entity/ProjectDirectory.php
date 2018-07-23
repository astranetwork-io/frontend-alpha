<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectDirectory
 *
 * @ORM\Table(name="project_directory")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\ProjectDirectoryRepository")
 */
class ProjectDirectory
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
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Directory", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var Directory
     */
    private $directory;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Project", inversedBy="projectDirectories", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var Project
     */
    private $project;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_root", type="boolean")
     */
    private $isRoot;


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
     * Set directory
     *
     * @param Directory $directory
     *
     * @return ProjectDirectory
     */
    public function setDirectory(Directory $directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get directory
     *
     * @return Directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set project
     *
     * @param Project $project
     *
     * @return ProjectDirectory
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set isRoot
     *
     * @param boolean $isRoot
     *
     * @return ProjectDirectory
     */
    public function setIsRoot($isRoot)
    {
        $this->isRoot = $isRoot;

        return $this;
    }

    /**
     * Get isRoot
     *
     * @return bool
     */
    public function getIsRoot()
    {
        return $this->isRoot;
    }
}
