<?php
namespace Astra\SharedBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
class Deposit
{
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
     * @return Deposit
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }
}