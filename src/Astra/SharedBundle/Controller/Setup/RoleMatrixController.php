<?php

namespace Astra\SharedBundle\Controller\Setup;

use Astra\SharedBundle\Controller\BaseController;
use Astra\SharedBundle\Services\Access;
use Symfony\Component\HttpFoundation\Request;

class RoleMatrixController extends BaseController
{
    public function configAction(Request $request)
    {

        return $this->render('AstraSharedBundle:Setup\RoleMatrix:config.html.twig',
            [
                'access' => Access::MODULES,
                'roles' => $this->getEm()->getRepository('AstraSharedBundle:UserRole')->findBy([],['name'=>'ASC']),
            ]
        );
    }

    public function saveAction(Request $request) {
        if (! $request->isMethod('post')) return $this->redirectToRoute('astra_shared_config_role_matrix_form');

        $form = $request->request->get('form');
        $roles = $this->getEm()->getRepository('AstraSharedBundle:UserRole')->findBy(['id' => $form['roles']]);

        foreach ($roles as $role) {
            $role->resetAccess();
            if (empty($form['access'][$role->getId()])) continue;
            foreach ($form['access'][$role->getId()] as $zone => $items) {
                foreach ($items as $action => $dummy) {
                    $role->openAccess($zone,$action);
                }
            }

            $this->getEm()->persist($role);
        }

        $this->getEm()->flush();

        return $this->redirectToRoute('astra_shared_config_role_matrix_form');
    }
}
