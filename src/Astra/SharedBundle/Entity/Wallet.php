<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Wallet
 *
 * @ORM\Table(name="wallet")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\WalletRepository")
 */
class Wallet
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
     * @ORM\Column(name="number", type="string", length=255, unique=true)
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="summ", type="bigint")
     */
    private $summ;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var User
     */
    private $user;


    public function __construct()
    {
        $uuId = Uuid::uuid4();
        $this->number = $uuId->toString();
        $this->summ = 0;
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
     * @return Wallet
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
     * Set user
     * @param User $user
     *
     * @return Wallet
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Wallet
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set summ
     *
     * @param integer $summ
     *
     * @return Wallet
     */
    public function setSumm($summ)
    {
        $this->summ = $summ*100;

        return $this;
    }

    /**
     * Get summ
     *
     * @return integer
     */
    public function getSumm()
    {
        return $this->summ/100;
    }
}
