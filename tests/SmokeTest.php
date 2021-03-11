<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlProvider()
    {
        // Home
        yield ['/'];
        // Movie show
        // @todo utiliser une base de test pour avoir des slugs
        // du genre 'film-1' etc.
        yield ['/movie/bruce-almighty'];
        // Login
        yield ['/login'];
    }
}
