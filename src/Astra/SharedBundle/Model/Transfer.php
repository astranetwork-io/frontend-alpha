<?php
namespace Astra\SharedBundle\Model;
use Astra\SharedBundle\Entity\Wallet;
use Symfony\Component\Validator\Constraints as Assert;
class Transfer
{
    /**
     * @var Wallet
     * @Assert\NotBlank()
     */

    protected $myWallet = null;

    /**
     * @var string
     * @Assert\NotBlank()
     */

    protected $targetNumber = '';

    /**
     * @var float
     * @Assert\NotBlank()
     */
    protected $amount = 100;

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Wallet
     */
    public function getMyWallet()
    {
        return $this->myWallet;
    }

    /**
     * @param Wallet $Wallet
     * @return self
     */
    public function setMyWallet(Wallet $Wallet)
    {
        $this->myWallet = $Wallet;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetNumber()
    {
        return $this->targetNumber;
    }

    /**
     * @param string $number
     * @return self
     */
    public function setTargetNumber(string $number)
    {
        $this->targetNumber = $number;
        return $this;
    }
}