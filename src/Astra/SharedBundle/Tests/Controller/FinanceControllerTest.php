<?php

namespace Astra\SharedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FinanceControllerTest extends WebTestCase
{
    public function testMywallet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'my-wallet');
    }

    public function testDeposit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Deposit');
    }

    public function testConfirmdeposit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'confirm-deposit');
    }

}
