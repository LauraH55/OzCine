<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_users_read", methods="GET")
     */
    public function read(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        // L'option --no-template de make:controller
        // a généré cette réponse JSON
        return $this->json($users);
    }
}
