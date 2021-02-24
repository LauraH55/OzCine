<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Classe de Fixture 
 * à exécuter avec la commande
 * `bin/console doctrine:fixtures:load`
 */
class AppFixtures extends Fixture
{
    // On règle nos valeurs ici
    const NB_GENRES = 20;
    const NB_MOVIES = 10;
    

    public function load(ObjectManager $manager)
    {   
        // Genres

        // Un tableau pour stocker nos genres
        $genresList = [];


         for ($i=1; $i <= self::NB_GENRES; $i++) {
            // Un genre
            $genre = new Genre();
            $genre->setName('Genre '.$i);

            // On ajoute le genre à la liste
            $genresList[] = $genre;

            $manager->persist($genre);
        }
        
        // Movies
        for ($i=1; $i <= self::NB_MOVIES; $i++) { 
            // Un film
            $movie = new Movie();
            $movie->setTitle('Film '.$i);
            // Génère un timestamp aléatoire de 1926 à maintenant
            $movie->setReleaseDate(new \DateTime('@'.rand(-1383899604, time())));
            $movie->setCreatedAt(new \DateTime());

            // On associe un genre
            // @todo choisir un genre au hasard
            $movie->addGenre($genresList[0]);

            // On le persist
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
