<?php

namespace Astra\SharedBundle\Form;

use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Form\Type\TagSelectorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'projects.name',
            'required' => true,
            'attr' => [
                'maxlength' => 250,
            ],
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'projects.description',
            'required' => true,
            'attr' => [
                'maxlength' => 10000,
                'editor'=>true,
            ],
        ]);

        $builder->add('new_logotype', FileType::class, [
            'label' => 'projects.newLogotype',
            'required' => false,
        ]);

        $builder->add('deathline', DateType::class, [
            'label' => 'projects.deathline',
            'widget' => 'single_text',
            'required' => false
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
        return 'user_about_form';
    }
}