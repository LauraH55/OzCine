<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{   
    /**
     * @Route("/test/movie/list", name="test_movie_list")
     */
    public function movieList()
    {
        // 1. On accède au Repository de l'entité Movie
        // Rappel FQCN = 'App\Entity\Movie' = Movie::class
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);

        // 2. On fait appel à la méthode findAll() du Repository
        $movies = $movieRepository->findAll();

        dump($movies);

        return $this->render('test/list.html.twig', [
            'movies' => $movies,
        ]);
    }


    /**
     * @Route("/test/movie/add", name="test_movie_add")
     */
    public function movieAdd(): Response
    {
        // 1. Créer une nouvelle entité
        $avatar = new Movie();
        $avatar->setTitle('Le cercle des poètes disparus');
        $avatar->setReleaseDate(new \DateTime('2012-12-20'));
        dump($avatar);

        // 2. On fait appel au manager d'entité de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // 3. On demande au Manager de se préparer à ajouter notre objet en BDD
        $entityManager->persist($avatar);

        // 4. Demande au Manager de sauvegarder l'objet en base
        // => requête SLQ exécutée
        $entityManager->flush();

        // Le /body permet à la toolbar de s'accrocher
        return new Response('Film ajouté.</body>');
    }
}
