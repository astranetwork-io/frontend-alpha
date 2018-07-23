<?php

namespace Astra\SharedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('new_photo', FileType::class, [
            'label' => 'user.avatarChange',
            'required' => false,
        ]);

        $builder->add('status', TextareaType::class, [
            'label' => 'user.status',
            'required' => false,
            'attr' => [
                'maxlength' => 250,
            ],
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'user.name',
            'required' => false,
            'attr' => [
                'maxlength' => 120,
            ],
        ]);

        $builder->add('surname', TextType::class, [
            'label' => 'user.surname',
            'required' => false,
            'attr' => [
                'maxlength' => 120,
            ],
        ]);

        $builder->add('birthday', DateType::class, [
            'label' => 'user.birthday',
            'widget' => 'single_text',
            'required' => false
        ]);

        $builder->add('skype', TextType::class, [
            'label' => 'user.skype',
            'required' => false,
            'attr' => [
                'maxlength' => 120,
            ],
        ]);

        $builder->add('phone', TextType::class, [
            'label' => 'user.phone',
            'required' => false,
            'attr' => [
                'maxlength' => 120,
            ],
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
        return 'user_form';
    }
}