<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectUsers
 *
 * @ORM\Table(name="project_users")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\ProjectUsersRepository")
 */
class ProjectUsers
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
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Project", inversedBy="users")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var Project
     */
    private $project;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="post", type="string", length=255)
     */
    private $post;


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
     * @return ProjectUsers
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
     * Set user
     *
     * @param User $user
     *
     * @return ProjectUsers
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
     * Set post
     *
     * @param string $post
     *
     * @return ProjectUsers
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPostFormatted()
    {
        if(mb_strlen($this->post))return $this->post;
        if($this->user===$this->project->getAuthor())return 'projects.author';
        return 'projects.employee';
    }
}
