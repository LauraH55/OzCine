<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

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
    const NB_CASTINGS = 15;
    const NB_PERSONS = 20;
    const NB_REVIEWS = 8;
    

    public function load(ObjectManager $manager)
    {   
        $faker = Factory::create('fr_FR');

        // Genres
        // Un tableau pour stocker nos genres
        $genresList = [];

         for ($i=1; $i <= self::NB_GENRES; $i++) {
            // Un genre
            $genre = new Genre();
            $genre->setName($faker->word());

            // On ajoute le genre à la liste
            $genresList[] = $genre;

            $manager->persist($genre);
        }

        // Person
        $personsList = [];
        for ($i=1; $i <= self::NB_PERSONS; $i++) {

            // Une personne 
            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());
            $person->setCreatedAt(new \DateTime());

            $personsList[] = $person;

             // On le persist
             $manager->persist($person);


        }

        
        // Movies
        for ($i=1; $i <= self::NB_MOVIES; $i++) { 
            // Un film
            $movie = new Movie();
            $movie->setTitle($faker->catchPhrase());
            // Génère un timestamp aléatoire de 1926 à maintenant
            $movie->setReleaseDate(new \DateTime('@'.rand(-1383899604, time())));
            $movie->setCreatedAt(new \DateTime());

            // On associe un genre
            // @todo choisir un genre au hasard
            $movie->addGenre($genresList[mt_rand(0, self::NB_GENRES - 1)]);

            // Genres 
            for ($j=1; $j <= self::NB_CASTINGS; $j++) {

                // Un casting
                $casting = new Casting();
                $casting->setMovie($movie);
                $casting->setPerson($personsList[mt_rand(0, self::NB_PERSONS - 1)]);
                $casting->setRole($faker->jobTitle());
                $casting->setCreditOrder($j);

                // On le persist
                $manager->persist($casting);
            }

            // Review 
            for ($j=1; $j <= self::NB_REVIEWS; $j++) {

                // Une review
                $review = new Review();
                $review->setMovie($movie);
                $review->setContent($faker->sentence());
                $review->setPublishedAt(new \DateTime());
        
                // On le persist
                $manager->persist($review);
            }
            
            // On le persist
            $manager->persist($movie);
        }
        $manager->flush();
    }
}
