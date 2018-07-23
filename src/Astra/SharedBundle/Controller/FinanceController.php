<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Entity\Wallet;
use Astra\SharedBundle\Form\DepositType;
use Astra\SharedBundle\Form\TransferType;
use Astra\SharedBundle\Form\WalletType;
use Astra\SharedBundle\Model\Deposit;
use Astra\SharedBundle\Model\Transfer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FinanceController extends BaseController
{

    public function MyWalletsAction()
    {
        $wallets = $this->getEm()->getRepository('AstraSharedBundle:Wallet')->findBy(['user'=>$this->getUser()],['name'=>'ASC','id'=>'ASC']);
        return $this->render('AstraSharedBundle:Finance:my_wallets.html.twig', ['wallets'=>$wallets]);
    }

    public function MyWalletsAddAction(Request $request)
    {
        $financeService = $this->get('astra.finance.service');
        $wallet = new Wallet();
        $wallet->setUser($this->getUser());
        $form = $this->createForm(WalletType::class, $wallet, []);
        $form->handleRequest($request);
        if(($form->isSubmitted()) && ($form->isValid()))
        {
            try
            {
                $financeService->saveWallet($wallet);
            }catch (\Exception $e)
            {
                return $this->render('AstraSharedBundle:Finance:my_wallets_add.html.twig', ['form'=>$form->createView(), 'mainError'=>$e->getMessage()]);
            }

            return new RedirectResponse($this->generateUrl('astra_shared_finance_my_wallets'));
        }
        return $this->render('AstraSharedBundle:Finance:my_wallets_add.html.twig', ['form'=>$form->createView(),'mainError'=>false]);
    }

    public function MyWalletAction()
    {
        $financeService = $this->get('astra.finance.service');
        $totalSumm = $financeService->getUserMoneyAmount($this->getUser());
        $allTransactionItem = $this->getEm()->getRepository('AstraSharedBundle:WalletTransactionItem')->
            findBy(['wallet'=>$financeService->getUserWallet($this->getUser())],['id'=>'DESC']);
        return $this->render('AstraSharedBundle:Finance:my_wallet.html.twig',
            ['totalSumm'=>$totalSumm,'allTransactionItem'=>$allTransactionItem]);
    }

    public function DepositAction(Request $request)
    {
        $wallet = $this->getEm()->getRepository('AstraSharedBundle:Wallet')->
        findOneBy(['id'=>$request->get('wallet',0),'user'=>$this->getUser()]);

        if(!$wallet)throw new NotFoundHttpException();

        $financeService = $this->get('astra.finance.service');
        $deposit = new Deposit();
        $form = $this->createForm(DepositType::class, $deposit, []);
        $form->handleRequest($request);
        if(($form->isSubmitted()) && ($form->isValid()))
        {
            try
            {
                $transaction = $financeService->addTransaction($deposit->getAmount(),null,$wallet);
            }catch (\Exception $e)
            {
                return $this->render('AstraSharedBundle:Finance:deposit.html.twig', ['form'=>$form->createView(),'mainError'=>$e->getMessage()]);
            }

            return new RedirectResponse($this->generateUrl('astra_shared_finance_confirm_deposit',['id'=>$transaction->getId()]));
        }

        return $this->render('AstraSharedBundle:Finance:deposit.html.twig', ['form'=>$form->createView(),'mainError'=>false,'wallet'=>$wallet]);
    }

    public function ConfirmDepositAction()
    {
        return $this->render('AstraSharedBundle:Finance:confirm_deposit.html.twig', []);
    }

    public function TransactionAction(Request $request)
    {

        $financeService = $this->get('astra.finance.service');
        $transfer = new Transfer();
        $wallets = $this->getEm()->getRepository('AstraSharedBundle:Wallet')->findBy(['user'=>$this->getUser()],['name'=>'ASC','id'=>'ASC']);
        $form = $this->createForm(TransferType::class, $transfer, ['myWallets'=>$wallets]);
        $form->handleRequest($request);
        if(($form->isSubmitted()) && ($form->isValid()))
        {
            try
            {
                $transaction = $financeService->addTransactionToNumber($transfer->getAmount(),$transfer->getMyWallet(),$transfer->getTargetNumber());
            }catch (\Exception $e)
            {
                return $this->render('AstraSharedBundle:Finance:transfer.html.twig', ['form'=>$form->createView(),'mainError'=>$e->getMessage()]);
            }

            return new RedirectResponse($this->generateUrl('astra_shared_finance_confirm_deposit',['id'=>$transaction->getId()]));
        }

        return $this->render('AstraSharedBundle:Finance:transfer.html.twig', ['form'=>$form->createView(),'mainError'=>false]);
    }

    public function ExchangeAction(Request $request)
    {
        return $this->render('AstraSharedBundle:Finance:exchange.html.twig');
    }

    public function StatisticsAction(Request $request)
    {
        return $this->render('AstraSharedBundle:Finance:statistics.html.twig');
    }

}
