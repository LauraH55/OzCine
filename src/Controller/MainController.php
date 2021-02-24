<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CastingRepository;
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
        // $movies = $movieRepository->findBy([], ['title' => 'ASC']);
        $movies = $movieRepository->findAllOrderedByTitleAsc();


        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Page d'un film
     * 
     * @Route("/movie/{id}", name="movie_show")
     */
    public function movieShow(Movie $movie, CastingRepository $castingRepository)
    {
        // On peut également récupérer les castings depuis le contrôleur
        // plutôt que de laisser Doctrine le faire depuis Twig
        // Ici, on va chercher les objets de type Casting dont le film est $movie
        // $castings = $castingRepository->findBy(['movie' => $movie], ['creditOrder' => 'ASC']);
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
        // dump($castings);

        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
            
        ]);
    }



}
