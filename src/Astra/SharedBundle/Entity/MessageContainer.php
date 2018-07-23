<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MessageContainer
 *
 * @ORM\Table(name="message_container")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\MessageContainerRepository")
 */
class MessageContainer
{
    const TYPE_PROJECT = 'project';
    const TYPE_CONTACT = 'contact';

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
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     */
    protected $type = '';

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Message", mappedBy="messageContainer")
     * @var Message[]
     */
    private $messages = [];

    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinTable(name="message_container_users",
     *      joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="message_container_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $users;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Project")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var Project
     */
    private $project;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\ContactList", mappedBy="messageContainer")
     * @var ArrayCollection
     */
    private $contactLists = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->contactLists = new ArrayCollection();
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
     * Add message
     *
     * @param Message $message
     *
     * @return MessageContainer
     */
    public function addMessage(Message $message)
    {
        $this->messages->add($message);

        return $this;
    }

    /**
     * Remove message
     *
     * @param \Astra\SharedBundle\Entity\Message $message
     */
    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return MessageContainer
     */
    public function addUser(User $user)
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MessageContainer
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set project
     *
     * @param Project $project
     *
     * @return MessageContainer
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
     * Add contactList
     *
     * @param \Astra\SharedBundle\Entity\ContactList $contactList
     *
     * @return MessageContainer
     */
    public function addContactList(\Astra\SharedBundle\Entity\ContactList $contactList)
    {
        $this->contactLists[] = $contactList;

        return $this;
    }

    /**
     * Remove contactList
     *
     * @param \Astra\SharedBundle\Entity\ContactList $contactList
     */
    public function removeContactList(\Astra\SharedBundle\Entity\ContactList $contactList)
    {
        $this->contactLists->removeElement($contactList);
    }

    /**
     * Get contactLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactLists()
    {
        return $this->contactLists;
    }
}
