<?php

namespace Symsite\Bundle\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoriesControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categories');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categories/delete');
    }

}
