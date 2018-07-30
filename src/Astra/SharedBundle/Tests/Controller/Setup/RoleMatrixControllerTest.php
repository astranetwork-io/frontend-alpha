<?php

namespace Astra\SharedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Setup\RoleMatrixControllerTest extends WebTestCase
{
    public function testConfig()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/config');
    }

}
