<?php

namespace Astra\SharedBundle\Controller;

class TagController extends BaseController
{
    public function AjaxLoadByNameAction()
    {
        $result = [['id'=>'1','text'=>'Hello World'],['id'=>'2','text'=>'Hello World2']];
        return $this->json($result);
    }

}
