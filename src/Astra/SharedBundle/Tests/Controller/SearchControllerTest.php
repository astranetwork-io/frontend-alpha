<?php

namespace Astra\SharedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSearchuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/SearchUser');
    }

}
