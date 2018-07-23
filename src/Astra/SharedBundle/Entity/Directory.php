<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Directory
 *
 * @ORM\Table(name="directory")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\DirectoryRepository")
 */
class Directory
{
    const TYPE_PUBLIC = 0;
    const TYPE_PRIVATE = 1;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="type", type="integer", length=255)
     */
    private $type = self::TYPE_PUBLIC;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Directory", inversedBy="childDirectories")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var Directory
     */
    private $parent;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Directory", mappedBy="parent")
     * @var Directory[]
     */
    private $childDirectories = [];


    public function __construct()
    {
        $this->childDirectories = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Directory
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
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Directory
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Directory
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Directory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Directory $parent
     * @return Directory
     */
    public function setParent(Directory $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param Directory $directory
     *
     * @return Directory
     */
    public function addChildDirectory(Directory $directory)
    {
        $this->childDirectories[] = $directory;

        return $this;
    }

    /**
     * @param Directory $directory
     */
    public function removeChildDirectory(Directory $directory)
    {
        $this->childDirectories->removeElement($directory);
    }

    /**
     * Get files
     *
     * @return Directory[]
     */
    public function getChildDirectories()
    {
        return $this->childDirectories;
    }
}
