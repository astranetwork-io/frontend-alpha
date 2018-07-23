<?php
namespace Astra\SharedBundle\Form\Type;
use Astra\SharedBundle\Entity\Tag;
use Astra\SharedBundle\Form\DataTransformer\TagToItemTagTransformer;
use Astra\SharedBundle\Model\NewTag;
use Astra\SharedBundle\Services\TagService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagSelectorType extends ChoiceType
{

    private $tagService;
    public function __construct(TagService $tagService, ChoiceListFactoryInterface $choiceListFactory)
    {
        parent::__construct($choiceListFactory);
        $this->tagService = $tagService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT,[$this,'test']);
        parent::buildForm($builder,$options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('attr',['select2-tags'=>true, 'select2-data-source'=>'http://astra.loc/tag/AjaxLoadByName',]);
        $resolver->setDefault('multiple',true);
        $resolver->setDefault('csrf_protection',false);
        $resolver->setDefault('validation_groups',false);
    }

    public function test(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    }

}