<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\ContactRepository")
 */
class Contact
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
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\ContactList", inversedBy="contacts")
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="CASCADE")
     * @var ContactList
     */
    private $contactList;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User", inversedBy="contacts")
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="CASCADE")
     * @var User
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

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
     * @return Contact
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
     * Set contactList
     *
     * @param \Astra\SharedBundle\Entity\ContactList $contactList
     *
     * @return Contact
     */
    public function setContactList(\Astra\SharedBundle\Entity\ContactList $contactList = null)
    {
        $this->contactList = $contactList;

        return $this;
    }

    /**
     * Get contactList
     *
     * @return \Astra\SharedBundle\Entity\ContactList
     */
    public function getContactList()
    {
        return $this->contactList;
    }

    /**
     * Set user
     *
     * @param \Astra\SharedBundle\Entity\User $user
     *
     * @return Contact
     */
    public function setUser(\Astra\SharedBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Astra\SharedBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getFullName()
    {
        if(!empty($this->name))return $this->getName();
        $result = [];
        foreach ($this->getContactList()->getContacts() as $contact)
        {
            if($contact===$this)continue;
            $result[] = $contact->getUser()->getFullUserName();
        }

        return join(', ',$result);
    }
}
