<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\FileRepository")
 */
class File
{
    const TYPE_IMAGE = 'image';

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="physical_name", type="string", length=255)
     */
    private $physicalName;

    /**
     * @var string
     *
     * @ORM\Column(name="physical_dir", type="string", length=1023)
     */
    private $physicalDir;

    /**
     * @var string
     *
     * @ORM\Column(name="asset", type="string", length=1023)
     */
    private $asset;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Directory")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var Directory
     */
    private $directory;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=250, nullable=true)
     */
    protected $type;

    /**
     * @Doctrine\ORM\Mapping\Column(name="sub_type",type="string", length=250, nullable=true)
     */
    protected $subType;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    public function __construct()
    {
        $this->created = new \DateTime();
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
     * @return File
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
    public function getPhysicalName()
    {
        return $this->physicalName;
    }

    /**
     * @param mixed $physicalName
     * @return File
     */
    public function setPhysicalName($physicalName)
    {
        $this->physicalName = $physicalName;
        return $this;
    }

    /**
     * @return Directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param Directory $directory
     * @return File
     */
    public function setDirectory(Directory $directory = null)
    {
        $this->directory = $directory;
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
     * @return File
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
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
     * @return File
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @param mixed $subType
     * @return File
     */
    public function setSubType($subType)
    {
        $this->subType = $subType;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getPhysicalDir()
    {
        return $this->physicalDir;
    }

    /**
     * @param mixed $physicalDir
     * @return File
     */
    public function setPhysicalDir($physicalDir)
    {
        $this->physicalDir = $physicalDir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAsset()
    {
        return $this->asset;
    }

    /**
     * @param mixed $asset
     * @return File
     */
    public function setAsset($asset)
    {
        $this->asset = $asset;
        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return File
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
}
