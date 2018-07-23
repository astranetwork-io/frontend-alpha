<?php

namespace Astra\SharedBundle\Form;

use Astra\SharedBundle\Entity\Wallet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TransferType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('myWallet', ChoiceType::class, [
            'label' => 'finance.wallet',
            'required' => true,
            'choices' => $options['myWallets'],
            'choice_value' => function($item) {
                if(!$item)return 0;
                return $item->getId();
            },
            'choice_label' => function($item, $key, $index) {
                return $item->getName().' ('.$item->getSumm().')';
            }
        ]);

        $builder->add('targetNumber', TextType::class, [
            'label' => 'finance.targetWalletNumber',
            'required' => true,
        ]);

        $builder->add('amount', NumberType::class, [
            'label' => 'finance.deposit.amount',
            'required' => true,
            'attr'=>[
                'class'=>'touchspin'
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'deposit_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['myWallets'=>[]]);
    }
}