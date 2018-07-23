<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->preExecute();
        if (!$this->user)
        {
            throw new NotFoundHttpException();
        }
    }

    protected function preExecute()
    {
        $this->user = $user = $this->getUser();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function trans($str, $params = [] ,$domain=null)
    {
        $translator = $this->get('translator');
        return $translator->trans($str,$params,$domain);
    }
}
