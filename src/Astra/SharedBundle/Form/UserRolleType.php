<?php

namespace Astra\SharedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserRolleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', TextType::class, [
            'label' => 'config.userRoles.entity.name',
            'required' => true,
            'attr'=>[
                'class'=>'touchspin'
            ]
        ]);

        $builder->add('isRoot', CheckboxType::class, [
            'label' => 'config.userRoles.entity.isRoot',
            'required' => false,
        ]);

        $builder->add('isDefault', CheckboxType::class, [
            'label' => 'config.userRoles.entity.isDefault',
            'required' => false,
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
        return 'user_rolle_form';
    }
}