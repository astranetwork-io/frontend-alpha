<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Utils\Values;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends BaseController
{
    public function SearchAction(Request $request)
    {
        $searchRequest = Values::getString($request->get('search',''));

        $searchService = $this->get('astra.search.service');

        $totalResult = 0;
        $users = [];

        if (!empty($searchRequest))
        {
            $users = $searchService->SearchUsers($searchRequest);
            $totalResult += count($users);
        }


        return $this->render('AstraSharedBundle:Search:search.html.twig',
            [
                'searchRequest' =>$searchRequest,
                'users'=>$users,
                'totalResult'=>$totalResult
            ]
        );
    }
}
