<?php

namespace Astra\SharedBundle\Form;

use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\Task;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\UserRepository;
use Astra\SharedBundle\Form\Type\TagSelectorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('caption', TextType::class, [
            'label' => 'task.caption',
            'required' => true,
            'attr' => [
                'maxlength' => 250,
            ],
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'task.description',
            'required' => true,
            'attr' => [
                'maxlength' => 10000,
                'editor'=>true,
            ],
        ]);

        /** @var Task $task */
        $task = $builder->getData();
        $builder->add('worker', EntityType::class, [
            'required' => $options['worker']['isRequire'],
            'label' => 'task.worker',
            'class'=> User::class,
            'choices'=> $options['worker']['users'],
            'choice_value' => function(User $user = null) {
                if(is_null($user)) return '';
                return $user->getId();
            },
            'choice_label' => function(User $user = null, $key, $index) {
                if(is_null($user)) return '';
                return $user->getFullUserName();
            }
        ]);

        $builder->add('startWork', DateType::class, [
            'label' => 'task.startWork',
            'widget' => 'single_text',
            'required' => false
        ]);

        $builder->add('endWork', DateType::class, [
            'label' => 'task.endWork',
            'widget' => 'single_text',
            'required' => false
        ]);

        $builder->add('isCalendar', CheckboxType::class, [
            'label' => 'task.isCalendar',
            'required' => false
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['worker'=>['isRequire'=>false,'users'=>[]]]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'task_form';
    }
}