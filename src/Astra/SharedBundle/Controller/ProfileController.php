<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Utils\Values;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileController extends BaseController
{
    public function viewAction($userName = false)
    {

        $contactService = $this->get('astra.contact.service');
        $userName = Values::getString($userName);
        if(empty($userName))
        {
            $user = $this->getUser();
        }
        else
        {
            $user = $this->getEm()->getRepository('AstraSharedBundle:User')->findOneBy(['usernameCanonical'=>$userName]);
        }

        if (!$user)
        {
            throw new NotFoundHttpException();
        }

        $isMyProfile = $user === $this->getUser();
        $contactList = null;
        $allLists = $contactService->getContactListForUsers([$this->getUser(),$user]);
        if(count($allLists))
        {
            $contactList = reset($allLists);
        }

        return $this->render('AstraSharedBundle:Profile:view.html.twig', ['user'=>$user, 'isMyProfile'=>$isMyProfile,'contactList'=>$contactList]);
    }
}
