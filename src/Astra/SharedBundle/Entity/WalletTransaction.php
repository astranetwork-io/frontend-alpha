<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WalletTransaction
 *
 * @ORM\Table(name="wallet_transaction")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\WalletTransactionRepository")
 */
class WalletTransaction
{
    const STATUS_WAITING = 0;
    const STATUS_CONFIRM = 1;
    const STATUS_CANCEL = 2;
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
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_processing", type="datetime", nullable=true)
     */
    private $dateProcessing;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="bigint")
     */
    private $amount;


    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\User")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="SET NULL")
     * @var User
     */
    private $author;

    public function __construct()
    {
        $this->dateCreate = new \DateTime();
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return WalletTransaction
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateProcessing
     *
     * @param \DateTime $dateProcessing
     *
     * @return WalletTransaction
     */
    public function setDateProcessing($dateProcessing)
    {
        $this->dateProcessing = $dateProcessing;

        return $this;
    }

    /**
     * Get dateProcessing
     *
     * @return \DateTime
     */
    public function getDateProcessing()
    {
        return $this->dateProcessing;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return WalletTransaction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return WalletTransaction
     */
    public function setAmount($amount)
    {
        $amount = floor($amount*100);
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount/100;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return WalletTransaction
     */
    public function setAuthor(User $author)
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
}
