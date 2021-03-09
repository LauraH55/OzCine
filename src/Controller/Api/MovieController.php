<?php

namespace App\Controller\Api;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API movies
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/api/movies", name="api_movies_read", methods="GET")
     */
    public function read(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        // Le 4ème argument représente le "contexte"
        // qui sera transmis au Serializer
        return $this->json($movies, 200, [], ['groups' => 'movies_read']);
    }
}
