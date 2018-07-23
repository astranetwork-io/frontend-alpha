<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\Wallet;
use Astra\SharedBundle\Entity\WalletTransaction;
use Astra\SharedBundle\Entity\WalletTransactionItem;
use Astra\SharedBundle\Utils\Values;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FinanceService
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, TokenStorageInterface $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function getUserWallet(User $user)
    {
        $wallet = $this->entityManager->getRepository('AstraSharedBundle:Wallet')->findOneBy(['user'=>$user]);
        if($wallet) return $wallet;

        return $this->addWalletForUser($user);
    }

    /**
     * @param User $user
     * @param string $name
     * @return Wallet
     */
    public function addWalletForUser(User $user, $name='default')
    {
        $wallet = new Wallet();
        $wallet->setName($name);
        $wallet->setUser($user);


        return $this->saveWallet($wallet);
    }

    public function saveWallet(Wallet $wallet)
    {
        $this->entityManager->persist($wallet);
        $this->entityManager->flush($wallet);
        return $wallet;
    }

    /**
     * @param float $amount
     * @param Wallet|null $walletFrom
     * @param Wallet|null $walletTo
     * @return WalletTransaction
     * @throws \Exception
     */
    public function addTransaction(float $amount, Wallet $walletFrom = null, Wallet $walletTo = null)
    {
        $amount = abs($amount);
        if ($walletFrom === $walletTo) throw new \Exception("finance.deposit.errors.wiseWallets");
        if ($amount <= 0) throw new \Exception("finance.deposit.errors.lowAmount");
        if (empty($walletFrom) && empty($walletTo)) throw new \Exception("finance.deposit.errors.noWallets");

        $transaction = new WalletTransaction();
        $transaction->setStatus(WalletTransaction::STATUS_CONFIRM);
        $transaction->setAmount($amount);
        $transaction->setAuthor($this->getUser());
        $transaction->setDateProcessing(new \DateTime());
        $this->entityManager->persist($transaction);

        if($walletFrom)
        {
            $transactionItemFrom = new WalletTransactionItem();
            $transactionItemFrom->setAmount(-1*$amount);
            $transactionItemFrom->setWallet($walletFrom);
            $transactionItemFrom->setType(WalletTransactionItem::TYPE_OUTCOME);
            $transactionItemFrom->setWallet($walletFrom);
            $transactionItemFrom->setWalletTransaction($transaction);
            $this->entityManager->persist($transactionItemFrom);

            $walletFrom->setSumm($walletFrom->getSumm()-$amount);
            $this->entityManager->persist($walletFrom);
        }

        if($walletTo)
        {
            $transactionItemTo = new WalletTransactionItem();
            $transactionItemTo->setAmount($amount);
            $transactionItemTo->setWallet($walletTo);
            $transactionItemTo->setType(WalletTransactionItem::TYPE_ICOME);
            $transactionItemTo->setWallet($walletTo);
            $transactionItemTo->setWalletTransaction($transaction);
            $this->entityManager->persist($transactionItemTo);
            $walletTo->setSumm($walletTo->getSumm()+$amount);
            $this->entityManager->persist($walletTo);
        }


        $this->entityManager->beginTransaction();
        $this->entityManager->flush();
        $this->entityManager->commit();

        return $transaction;
    }

    /**
     * @param float $amount
     * @param Wallet $walletFrom
     * @param string $number
     * @return WalletTransaction
     * @throws \Exception
     */
    public function addTransactionToNumber(float $amount, Wallet $walletFrom, string $number)
    {
        $number = Values::getString($number);
        if(empty($number))
        {
            throw new \Exception('finance.deposit.errors.noSetTargetNumber');
        }
        $walletTo = $this->entityManager->getRepository('AstraSharedBundle:Wallet')->findOneBy(['number'=>$number]);

        if(!$walletTo)
        {
            throw new \Exception('finance.deposit.errors.wrongSetTargetNumber');
        }

        if($walletTo===$walletFrom)
        {
            throw new \Exception('finance.deposit.errors.wrongSetTargetNumber');
        }

        if($amount>$walletFrom->getSumm())
        {
            throw new \Exception('finance.deposit.errors.noMoney');
        }

        return $this->addTransaction($amount,$walletFrom,$walletTo);
    }

    /**
     * @param $amount
     * @param User $user
     * @return WalletTransaction
     * @throws \Exception
     */
    public function addDepositeForUser($amount, User $user)
    {
        $wallet = $this->getUserWallet($user);
        if(!$wallet)
        {
            throw new \Exception('finance.deposit.errors.noFindWallets');
        }
        return $this->addTransaction($amount,null,$wallet);
    }

    public function getUserMoneyAmount(User $user)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $wallet = $this->getUserWallet($user);
        if(!$wallet) return 0;

        $qb->select('SUM(i.amount)')
            ->from('AstraSharedBundle:WalletTransactionItem','i')
            ->innerJoin('i.walletTransaction','t')
            ->where('t.status = :s')->setParameter('s',WalletTransaction::STATUS_CONFIRM)
            ->andWhere('i.wallet = :w')->setParameter('w',$wallet)
        ;

        return $qb->getQuery()->getSingleScalarResult()/100;
    }

    /**
     * @return User|boolean
     */
    protected function getUser()
    {
        if((!$this->tokenStorage) || (!$this->tokenStorage->getToken())|| (!$this->tokenStorage->getToken()->getUser())) return false;
        return $this->tokenStorage->getToken()->getUser();
    }
}