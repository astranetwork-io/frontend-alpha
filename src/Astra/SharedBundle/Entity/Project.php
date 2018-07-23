<?php

namespace Astra\SharedBundle\Entity;

use Astra\SharedBundle\Model\NewTag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\ProjectRepository")
 */
class Project
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var User
     */
    private $author;

    /**
     * @Assert\File(mimeTypes={ "image/gif", "image/jpeg", "image/pjpeg", "image/png" })
     */
    private $new_logotype;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\File")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var File
     */
    private $logotype;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\ProjectUsers", mappedBy="project")
     * @var ArrayCollection
     */
    private $users = [];

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\ProjectFile", mappedBy="project")
     * @var ProjectFile[]
     */
    private $files = [];

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\ProjectTag", mappedBy="project")
     * @var ProjectTag[]
     */
    private $projectTags = [];

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\ProjectDirectory", mappedBy="project")
     * @var ProjectDirectory[]
     */
    private $projectDirectories = [];

    /**
     * @var NewTag[]
     */
    private $newTags = null;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \DateTime
     * @ORM\Column(name="deathline", type="date", nullable=true)
     */
    protected $deathline;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\TaskList", mappedBy="project")
     * @var TaskList[]
     */
    private $taskList = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->projectTags = new ArrayCollection();
        $this->projectDirectories = new ArrayCollection();
        $this->taskList = new ArrayCollection();
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
     * @return Project
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
     * Set description
     *
     * @param string $description
     *
     * @return Project
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
     * Set author
     *
     * @param User $author
     *
     * @return Project
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add user
     *
     * @param ProjectUsers $user
     *
     * @return Project
     */
    public function addUser(ProjectUsers $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param ProjectUsers $user
     */
    public function removeUser(ProjectUsers $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return User[]
     */
    public function getUsersAsEntity()
    {
        $projectUsers = $this->getUsers();
        $result = [];

        foreach ($projectUsers as $projectUser)
        {
            $result[$projectUser->getUser()->getId()] = $projectUser->getUser();
        }

        return $result;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isUserInProject(User $user)
    {
        $users = $this->getUsersAsEntity();
        return in_array($user,$users,true);
    }

    /**
     * Add file
     *
     * @param ProjectFile $file
     *
     * @return Project
     */
    public function addFile(ProjectFile $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param ProjectFile $file
     */
    public function removeFile(ProjectFile $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return ProjectFile[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ProjectDirectory $projectDirectory
     *
     * @return Project
     */
    public function addProjectDirectory(ProjectDirectory $projectDirectory)
    {
        $this->projectDirectories[] = $projectDirectory;

        return $this;
    }

    /**
     * @param ProjectDirectory $projectDirectory
     */
    public function removeProjectDirectory(ProjectDirectory $projectDirectory)
    {
        $this->projectDirectories->removeElement($projectDirectory);
    }

    /**
     * Get files
     *
     * @return ProjectDirectory[]
     */
    public function getProjectDirectories()
    {
        return $this->projectDirectories;
    }

    /**
     * Add tag
     *
     * @param ProjectTag $tag
     *
     * @return Project
     */
    public function addProjectTag(ProjectTag $tag)
    {
        $this->projectTags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param ProjectTag $tag
     */
    public function removeProjectTag(ProjectTag $tag)
    {
        $this->projectTags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectTags()
    {
        return $this->projectTags;
    }

    /**
     * Get tags
     *
     * @return Tag[]
     */
    public function getNewTags()
    {
        if(is_null($this->newTags)) $this->newTags = [];
        foreach ($this->getProjectTags() as $projectTag)
        {
            $this->newTags[] = $projectTag->getTag();
        }
        return $this->newTags;
    }

    /**
     * @param $newTags
     * @return $this
     */
    public function setNewTags($newTags)
    {
        $this->newTags = $newTags;
        return $this;
    }

     /**
      * @return mixed
      */
    public function getNewLogotype()
    {
        return $this->new_logotype;
    }

    /**
     * @param mixed $new_logotype
     * @return Project
     */
    public function setNewLogotype($new_logotype)
    {
        $this->new_logotype = $new_logotype;
        return $this;
    }

    /**
     * @return File
     */
    public function getLogotype()
    {
        return $this->logotype;
    }

    /**
     * @param File $logotype
     * @return Project
     */
    public function setLogotype(File $logotype)
    {
        $this->logotype = $logotype;
        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Project
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
     * Set deathline
     *
     * @param \DateTime $deathline
     *
     * @return Project
     */
    public function setDeathline($deathline)
    {
        $this->deathline = $deathline;

        return $this;
    }

    /**
     * Get deathline
     *
     * @return \DateTime
     */
    public function getDeathline()
    {
        return $this->deathline;
    }

    /**
     * Add taskList
     *
     * @param \Astra\SharedBundle\Entity\TaskList $taskList
     *
     * @return Project
     */
    public function addTaskList(TaskList $taskList)
    {
        $this->taskList[] = $taskList;

        return $this;
    }

    /**
     * Remove taskList
     *
     * @param \Astra\SharedBundle\Entity\TaskList $taskList
     */
    public function removeTaskList(TaskList $taskList)
    {
        $this->taskList->removeElement($taskList);
    }

    /**
     * Get taskList
     *
     * @return \Doctrine\Common\Collections\Collection | TaskList[]
     */
    public function getTaskList()
    {
        return $this->taskList;
    }
}
