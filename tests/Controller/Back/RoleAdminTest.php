<?php

namespace App\Tests\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleAdminTest extends WebTestCase
{
    /**
     * @dataProvider urlProviderAdminGetSuccessful
     */
    public function testAdminGetSuccessful($url)
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@admin.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urlProviderAdminGetSuccessful()
    {
        //yield ['/'];
        yield ['/review/add'];
        yield ['/back/movie/add'];
        yield ['/back/movie/edit/1'];
        //yield ['/back/user/delete/3132'];
    }
}
