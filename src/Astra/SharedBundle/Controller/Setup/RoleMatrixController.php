<?php

namespace Astra\SharedBundle\Controller\Setup;

use Astra\SharedBundle\Controller\BaseController;

class RoleMatrixController extends BaseController
{
    public function configAction()
    {
        return $this->render('AstraSharedBundle:Setup\RoleMatrix:config.html.twig', []);
    }

}
