<?php

namespace Astra\SharedBundle\Entity;

use Astra\SharedBundle\Model\TagContainerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectTag
 *
 * @ORM\Table(name="project_tag")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\ProjectTagRepository")
 */
class ProjectTag implements TagContainerInterface
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
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Project", inversedBy="projectTags")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var Project
     */
    private $project;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Tag")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var Tag
     */
    private $tag;


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
     * @param Project $project
     *
     * @return ProjectTag
     */
    public function setProject($project)
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
     * Set tag
     *
     * @param Tag $tag
     *
     * @return ProjectTag
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
