<?php
// src/Acme/ApiBundle/Entity/User.php
namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table("users")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=120, nullable=true)
     */
    protected $name = '';

    /**
     * @var string
     * @ORM\Column(name="surname", type="string", length=120, nullable=true)
     */
    protected $surname = '';


    /**
     * @var \DateTime
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @var string
     * @ORM\Column(name="skype", type="string", length=120, nullable=true)
     */
    protected $skype = '';

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=120, nullable=true)
     */
    protected $phone = '';

    /**
     * @Assert\File(mimeTypes={ "image/gif", "image/jpeg", "image/pjpeg", "image/png" })
     */
    private $new_photo;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\File")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var File
     */
    private $photo;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", length=254, nullable=true)
     */
    protected $status = '';

    /**
     * @var string
     * @ORM\Column(name="about_me", type="string", length=2047, nullable=true)
     */
    protected $aboutMe = '';

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Task", mappedBy="author")
     * @var Task[]
     */
    protected $createdTasks;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Task", mappedBy="worker")
     * @var Task[]
     */
    protected $workTasks;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\TaskList", mappedBy="user")
     * @var TaskList[]
     */
    protected $taskList;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Message", mappedBy="user")
     * @var Message[]
     */
    protected $my_messages;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Contact", mappedBy="user")
     * @var ArrayCollection
     */
    protected $contacts;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Contact", mappedBy="user")
     * @var ArrayCollection
     */

    /**
     * @var ArrayCollection
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Astra\SharedBundle\Entity\UserRole")
     * @Doctrine\ORM\Mapping\JoinTable(name="user_roles",
     *      joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $userRoles;


    public function __construct()
    {
        parent::__construct();
        $this->taskList = new ArrayCollection();
        $this->workTasks = new ArrayCollection();
        $this->createdTasks = new ArrayCollection();
        $this->my_messages = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday = null)
    {
        if (!($birthday instanceof \DateTime ))$birthday = null;
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName($name = null)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return User
     */
    public function setSurname($surname = null)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param string $skype
     * @return User
     */
    public function setSkype($skype = null)
    {
        $this->skype = $skype;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewPhoto()
    {
        return $this->new_photo;
    }

    /**
     * @param mixed $new_photo
     * @return User
     */
    public function setNewPhoto($new_photo = null)
    {
        $this->new_photo = $new_photo;
        return $this;
    }

    /**
     * @return File
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param File $photo
     * @return User
     */
    public function setPhoto(File $photo = null)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * @param string $aboutMe
     * @return User
     */
    public function setAboutMe(string $aboutMe = null)
    {
        $this->aboutMe = $aboutMe;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return User
     */
    public function setStatus(string $status = null)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Add createdTask
     *
     * @param \Astra\SharedBundle\Entity\Task $createdTask
     *
     * @return User
     */
    public function addCreatedTask(Task $createdTask)
    {
        $this->createdTasks[] = $createdTask;

        return $this;
    }

    /**
     * Remove createdTask
     *
     * @param \Astra\SharedBundle\Entity\Task $createdTask
     */
    public function removeCreatedTask(Task $createdTask)
    {
        $this->createdTasks->removeElement($createdTask);
    }

    /**
     * Get createdTasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedTasks()
    {
        return $this->createdTasks;
    }

    /**
     * Add workTask
     *
     * @param \Astra\SharedBundle\Entity\Task $workTask
     *
     * @return User
     */
    public function addWorkTask(Task $workTask)
    {
        $this->workTasks[] = $workTask;

        return $this;
    }

    /**
     * Remove workTask
     *
     * @param \Astra\SharedBundle\Entity\Task $workTask
     */
    public function removeWorkTask(Task $workTask)
    {
        $this->workTasks->removeElement($workTask);
    }

    /**
     * Get workTasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkTasks()
    {
        return $this->workTasks;
    }

    public function getFullUserName()
    {
        $result = [];
        if(!empty($this->name))$result[] = $this->name;
        if(!empty($this->surname))$result[] = $this->surname;

        if(empty($result))$result[] = $this->username;

        return join(' ',$result);
    }

    /**
     * Add taskList
     *
     * @param \Astra\SharedBundle\Entity\TaskList $taskList
     *
     * @return User
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaskList()
    {
        return $this->taskList;
    }

    /**
     * Add myMessage
     *
     * @param Message $myMessage
     *
     * @return User
     */
    public function addMyMessage(Message $myMessage)
    {
        $this->my_messages->add($myMessage);

        return $this;
    }

    /**
     * Remove myMessage
     *
     * @param Message $myMessage
     */
    public function removeMyMessage(Message $myMessage)
    {
        $this->my_messages->removeElement($myMessage);
    }

    /**
     * Get myMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyMessages()
    {
        return $this->my_messages;
    }

    /**
     * Add contact
     *
     * @param \Astra\SharedBundle\Entity\Contact $contact
     *
     * @return User
     */
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \Astra\SharedBundle\Entity\Contact $contact
     */
    public function removeContact(Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param UserRole $userRole
     *
     * @return self
     */
    public function addUserRole(UserRole $userRole)
    {
        $this->userRoles->add($userRole);

        return $this;
    }

    /**
     * @param UserRole $userRole
     */
    public function removeUserRole(UserRole $userRole)
    {
        $this->userRoles->removeElement($userRole);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRole()
    {
        return $this->userRoles;
    }

}
