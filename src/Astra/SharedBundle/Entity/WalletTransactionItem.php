<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WalletTransactionItem
 *
 * @ORM\Table(name="wallet_transaction_item")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\WalletTransactionItemRepository")
 */
class WalletTransactionItem
{
    const TYPE_ICOME = 1;
    const TYPE_OUTCOME = -1;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\WalletTransaction")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="CASCADE")
     * @var WalletTransaction
     */
    private $walletTransaction;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Astra\SharedBundle\Entity\Wallet")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true, onDelete="RESTRICT")
     * @var Wallet
     */
    private $wallet;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="bigint")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;


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
     * Set walletTransaction
     *
     * @param WalletTransaction $walletTransaction
     *
     * @return WalletTransactionItem
     */
    public function setWalletTransaction(WalletTransaction $walletTransaction)
    {
        $this->walletTransaction = $walletTransaction;

        return $this;
    }

    /**
     * Get walletTransaction
     *
     * @return WalletTransaction
     */
    public function getWalletTransaction()
    {
        return $this->walletTransaction;
    }

    /**
     * Set wallet
     *
     * @param Wallet $wallet
     *
     * @return WalletTransactionItem
     */
    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get wallet
     *
     * @return Wallet
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return WalletTransactionItem
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
     * Set type
     *
     * @param integer $type
     *
     * @return WalletTransactionItem
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}
