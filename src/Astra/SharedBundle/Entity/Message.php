<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\MessageRepository")
 */
class Message
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
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User", inversedBy="my_messages")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\MessageContainer", inversedBy="messages")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var MessageContainer
     */
    private $messageContainer;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinTable(name="message_readers",
     *      joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="message_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $readers;

    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Astra\SharedBundle\Entity\File")
     * @Doctrine\ORM\Mapping\JoinTable(name="message_files",
     *      joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="message_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->readers = new ArrayCollection();
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Message
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
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * @return bool
     */
    private $isRead = false;
    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     * @return $this
     */
    public function setIsRead(bool $isRead)
    {
        $this->isRead = $isRead;
        return $this;
    }


    /**
     * Add reader
     *
     * @param User $reader
     *
     * @return Message
     */
    public function addReader(User $reader)
    {
        $this->readers->add($reader);

        return $this;
    }

    /**
     * Remove reader
     *
     * @param User $reader
     */
    public function removeReader(User $reader)
    {
        $this->readers->removeElement($reader);
    }

    /**
     * Get readers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReaders()
    {
        return $this->readers;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Message
     */
    public function setUser(User $user)
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
     * Set messageContainer
     *
     * @param MessageContainer $messageContainer
     *
     * @return Message
     */
    public function setMessageContainer(MessageContainer $messageContainer)
    {
        $this->messageContainer = $messageContainer;

        return $this;
    }

    /**
     * Get messageContainer
     *
     * @return MessageContainer
     */
    public function getMessageContainer()
    {
        return $this->messageContainer;
    }

    /**
     * Add file
     *
     * @param File $file
     *
     * @return Message
     */
    public function addFile(File $file)
    {
        $this->files->add($file);

        return $this;
    }

    /**
     * Remove file
     *
     * @param File $file
     */
    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
