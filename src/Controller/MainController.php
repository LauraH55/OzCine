<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @Route("/", name="home")
     */
    public function home(MovieRepository $movieRepository): Response
    {
        // Tous les films par ordre alphabétique
        $movies = $movieRepository->findBy([], ['title' => 'ASC']);


        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Page d'un film
     * 
     * @Route("/movie/{id}", name="movie_show")
     */
    public function movieShow(Movie $movie)
    {
        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
        ]);
    }



}
