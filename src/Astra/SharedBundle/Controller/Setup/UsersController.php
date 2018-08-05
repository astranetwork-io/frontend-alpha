<?php

namespace Astra\SharedBundle\Controller\Setup;

use Astra\SharedBundle\Controller\BaseController;
use Astra\SharedBundle\Entity\UserRole;
use Astra\SharedBundle\Form\UserAdminType;
use Astra\SharedBundle\Form\UserRolleType;
use Astra\SharedBundle\Services\SharedVariableService;
use FOS\UserBundle\Model\UserManagerInterface;
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
        $user = $this->getEm()->getRepository('AstraSharedBundle:User')->find($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_USER_ADMIN_EDIT,$user);

        $fileService = $this->get('astra.file.service');
        $form = $this->createForm(UserAdminType::class, $user, []);
        $form->handleRequest($request);
        if(($form->isSubmitted()) && ($form->isValid()))
        {
            if ($user->getNewPhoto())
            {
                $file = $fileService->saveUploadetFile($user->getNewPhoto(),$user,'avatars');
                if (!empty($file)) $user->setPhoto($file);
            }

            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return new RedirectResponse($this->generateUrl('astra_shared_config_users_index'));
        }

        return $this->render('AstraSharedBundle:Setup\Users:edit.html.twig', ['form'=>$form->createView(), 'user' => $user]);
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
