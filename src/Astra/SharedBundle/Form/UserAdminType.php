<?php

namespace Astra\SharedBundle\Form;


use Astra\SharedBundle\Entity\UserRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class UserAdminType extends UserType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder->add('getUserRole', EntityType::class, [
            'required' => false,
            'label' => 'user.rolle',
            'class' => UserRole::class,
            'multiple' => true,
            'choice_value' => function(UserRole $role = null) {
                if(is_null($role)) return '';
                return $role->getId();
            },
            'choice_label' => function(UserRole $role = null, $key, $index) {
                if(is_null($role)) return '';
                return $role->getName();
            },
        ]);
    }
}