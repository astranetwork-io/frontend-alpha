<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactList
 *
 * @ORM\Table(name="contact_list")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\ContactListRepository")
 */
class ContactList
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
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\MessageContainer", inversedBy="contactLists")
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="CASCADE")
     * @var MessageContainer
     */
    private $messageContainer;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Astra\SharedBundle\Entity\Contact", mappedBy="contactList")
     * @var ArrayCollection
     */
    private $contacts = [];

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->contacts = new ArrayCollection();
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
     * @return ContactList
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
     * Set messageContainer
     *
     * @param \Astra\SharedBundle\Entity\MessageContainer $messageContainer
     *
     * @return ContactList
     */
    public function setMessageContainer(\Astra\SharedBundle\Entity\MessageContainer $messageContainer = null)
    {
        $this->messageContainer = $messageContainer;

        return $this;
    }

    /**
     * Get messageContainer
     *
     * @return \Astra\SharedBundle\Entity\MessageContainer
     */
    public function getMessageContainer()
    {
        return $this->messageContainer;
    }

    /**
     * Add contact
     *
     * @param \Astra\SharedBundle\Entity\Contact $contact
     *
     * @return ContactList
     */
    public function addContact(\Astra\SharedBundle\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \Astra\SharedBundle\Entity\Contact $contact
     */
    public function removeContact(\Astra\SharedBundle\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return Contact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param User $user
     * @return Contact|null
     */
    public function getUserContact(User $user)
    {
        foreach ($this->getContacts() as $contact)
        {
            if ($contact->getUser() === $user)return $contact;
        }
        return null;
    }
}
