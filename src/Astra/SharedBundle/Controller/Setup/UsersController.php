<?php

namespace Astra\SharedBundle\Controller\Setup;

use Astra\SharedBundle\Controller\BaseController;
use Astra\SharedBundle\Entity\UserRole;
use Astra\SharedBundle\Form\UserRolleType;
use Astra\SharedBundle\Services\SharedVariableService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersController extends BaseController
{
    public function indexAction()
    {
        $users = $this->getEm()->getRepository('AstraSharedBundle:User')->findBy([],['username' => 'ASC']);
        return $this->render('AstraSharedBundle:Setup\Users:index.html.twig', ['users' => $users]);
    }

    public function editAction(Request $request, $id)
    {
        $userRolle = new UserRole();
        $template = 'add.html.twig';
        if ($id > 0)
        {
            $template = 'edit.html.twig';
            $userRolle = $this->getEm()->find('AstraSharedBundle:UserRole',$id);
            if (!$userRolle) throw new NotFoundHttpException();

            $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_USER_ROLE,$userRolle);
        }

        $userRoleService = $this->get('astra.user_role.service');
        $form = $this->createForm(UserRolleType::class, $userRolle, []);
        $form->handleRequest($request);

        $mainError = false;

        if ($form->isValid() && $form->isSubmitted()) {
            try
            {
                $userRoleService->saveUserRolle($userRolle,$this->getUser());
            }catch (\Exception $e)
            {
                return $this->render('AstraSharedBundle:Setup\Roles:'.$template, ['form'=>$form->createView(), 'mainError'=>$e->getMessage()]);
            }
            return new RedirectResponse($this->generateUrl('astra_shared_config_roles_index'));
        }

        return $this->render('AstraSharedBundle:Setup\Roles:'.$template, ['form'=>$form->createView(),'mainError'=>$mainError]);
    }

    public function deleteAction($id)
    {
        $userRolle = $this->getEm()->find('AstraSharedBundle:UserRole',$id);
        if (!$userRolle) throw new NotFoundHttpException();
        $userRoleService = $this->get('astra.user_role.service');
        try
        {
            $userRoleService->removeUserRolle($userRolle);
        }catch (\Exception $e){

        }

        return new RedirectResponse($this->generateUrl('astra_shared_config_roles_index'));
    }

}
