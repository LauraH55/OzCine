<?php

namespace App\Tests\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleUserTest extends WebTestCase
{
    /**
     * Le User peut afficher le form Review
     */
    public function testAddReview()
    {
        // @see https://symfony.com/doc/current/testing.html#logging-in-users-authentication
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user@user.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/review/add');
        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Ajouter une critique');
    }
}
