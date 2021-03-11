<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Etendre de WebTestCase nous permet de faire des tests fonctionnels
 * sur l'appli Symfony
 */
class MainControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        // Status 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Contenu du H1 
        $this->assertSelectorTextContains('h1', 'Tous les films');
    }

    /**
     * L'anonyme n'a pas accès à l'ajout d'une critique
     */
    public function testReviewAddFailure()
    {
        // Crée un client
        $client = static::createClient();
        // Exécute une requête en GET sur la route '/review/add'
        $crawler = $client->request('GET', '/review/add');

        // La réponse a un statut 3xx car redirection vers /login
        $this->assertResponseRedirects();
        // $this->assertResponseStatusCodeSame(302);
    }

    /**
     * L'anonyme n'as pas accès à l'ajout d'une critique
     * En POST
     */
    public function testReviewAddFailurePost()
    {
        // Crée un client
        $client = static::createClient();
        // Exécute une requête en GET sur la route '/review/add'
        $crawler = $client->request('POST', '/review/add');

        // La réponse a un statut 3xx car redirection vers /login
        $this->assertResponseRedirects();
        // $this->assertResponseStatusCodeSame(302);
    }


}
