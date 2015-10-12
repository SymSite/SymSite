<?php

namespace Symsite\Bundle\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tags');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tags/delete');
    }

}
