<?php

namespace Astra\SharedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagControllerTest extends WebTestCase
{
    public function testAjaxloadbyname()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/AjaxLoadByName');
    }

}
