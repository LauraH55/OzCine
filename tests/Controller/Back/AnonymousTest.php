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
        yield ['/back/user/browse'];
        yield ['/back/user/add'];
        yield ['/back/user/read/35'];
        yield ['/back/user/edit/11'];
        yield ['/admin'];
        yield ['/admin/add'];
        yield ['/admin/edit/11'];
    }
}
