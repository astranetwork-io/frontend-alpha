<?php

namespace Astra\SharedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyProfileControllerTest extends WebTestCase
{
    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Edit');
    }

    public function testFinance()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Finance');
    }

}
