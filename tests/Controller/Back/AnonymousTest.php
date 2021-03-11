<?php

namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonymousTest extends WebTestCase
{
    /**
     * Annotation suivi du nom de la méthode qui fourni les données
     *
     * @dataProvider urlProvider
     */
    public function testRedirectInGet($url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        // On est bien redirigé vers le login
        $this->assertResponseStatusCodeSame(302);
    }

    public function urlProvider()
    {
        yield ['/admin/user/browse'];
        yield ['/admin/user/add'];
        yield ['/admin/user/read/1'];
        yield ['/admin/user/edit/1'];
        yield ['/admin'];
        yield ['/admin/add'];
        yield ['/admin/edit/1'];
    }
}
