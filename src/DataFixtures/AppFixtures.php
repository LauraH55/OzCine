<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\MovieDbProvider;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Classe de Fixture
 * à exécuter avec la commande
 * `bin/console doctrine:fixtures:load`
 */
class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    // On règle nos valeurs ici
    const NB_GENRES = 20;
    const NB_MOVIES = 10;
    // On veut par ex. 5 reviews par film
    const NB_REVIEWS = 5 * self::NB_MOVIES;
    // On veut par ex. 8 casting par film
    const NB_CASTINGS = 8 * self::NB_MOVIES;
    // On veut par ex. le double de castings
    const NB_PERSONS = 2 * self::NB_CASTINGS;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        // Toujours les mêmes données
        // $faker->seed('Oz');

        // Fourniture de notre Provider à Faker
        $faker->addProvider(new MovieDbProvider());

        // Utilisateurs
        //! Ici pas de faker
        // => on veut controler les données de ces 3 users
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$E6eVPobLnnNG+DRGtjb5xQ$GDy87lQ1jEn6hpvSuYgzFy8+xPCXA2iB4zYFpmom0PI');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // MANAGER
        $userManager = new User();
        $userManager->setEmail('manager@manager.com');
        // manager
        $userManager->setPassword('$argon2id$v=19$m=65536,t=4,p=1$dWhGK3omSDcZFm855F8QpA$wPS1ylHDw5KTTESc4p4wqv/jE4Js54mP8AHbLmorXl8');
        $userManager->setRoles(['ROLE_MANAGER']);
        $manager->persist($userManager);

        $admin = new User();
        $admin->setEmail('admin@admin.com');
        // admin
        $admin->setPassword('$argon2id$v=19$m=65536,t=4,p=1$vB/QqWTUKbdlGBB4/XKnnw$ZaW222CFopGLs8Y86UksdMJ0E+YQAr2+9JC7kFhvBlU');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        
        
        // Genres
        // Un tableau pour stocker nos genres
        $genresList = [];

        for ($i = 1; $i <= self::NB_GENRES; $i++) {
            // Un genre
            $genre = new Genre();
            // Modifier unique() de Faker
            // @see https://fakerphp.github.io/#modifiers
            $genre->setName($faker->unique()->movieGenre());

            // On ajoute le genre à la liste
            // /!\ Attention on push à partir de l'index 0
            $genresList[] = $genre;

            $manager->persist($genre);
        }

        // Préparons un tableau pour stocker les personnes
        // et y accéder depuis la création des castings
        $personsList = [];

        for ($i = 1; $i <= self::NB_PERSONS; $i++) {
            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());
            // On persist
            $manager->persist($person);
            // On stocke la personne pour usage ultérieur
            $personsList[] = $person;
        }

        // Movies

        // On crée ce tableau pour associer les films aux Reviews
        $moviesList = [];

        for ($i = 1; $i <= self::NB_MOVIES; $i++) { 
            // Un film
            $movie = new Movie();
            $movie->setTitle($faker->unique()->movieTitle());
            // SLUG
            $slug = $this->slugger->slug($movie->getTitle(), $separator = '-', $locale = null);
            $movie->setSlug($slug);
            //dump($movie);
            // Génère un timestamp aléatoire de 1926 à maintenant
            $movie->setReleaseDate($faker->dateTimeBetween('-100 years'));
            $movie->setCreatedAt(new \DateTime());
            
            

            // On associe de 1 à 3 genres au hasard
            // /!\ On va gérer l'unicité avec shuffle()
            shuffle($genresList);
            
            for ($r = 0; $r < mt_rand(1, 3); $r++) {
                // On va chercher l'index $r dans le tableau mélangé
                // => l'unicité est garanti
                $randomGenre = $genresList[$r];
                $movie->addGenre($randomGenre);
            }

            $moviesList[] = $movie;

            // On le persist
            $manager->persist($movie);
        }

        // Les reviews
        for ($i = 1; $i <= self::NB_REVIEWS; $i++) {
            $review = new Review();
            $review->setContent($faker->text());
            $review->setPublishedAt(new \DateTime());

            // On va chercher un film au hasard dans la liste des films créée au-dessus
            // Variante avec mt_rand et count
            $randomMovie = $moviesList[mt_rand(0, count($moviesList) - 1)];
            $review->setMovie($randomMovie);

            // On persist
            $manager->persist($review);
        }

        // Les castings
        for ($i = 1; $i < self::NB_CASTINGS; $i++) {
            $casting = new Casting();
            $casting->setRole($faker->name());
            $casting->setCreditOrder($faker->numberBetween(1, 10));;

            // On va chercher un film au hasard dans la liste des films créée au-dessus
            // Variante avec mt_rand et count
            $randomMovie = $moviesList[mt_rand(0, count($moviesList) - 1)];
            $casting->setMovie($randomMovie);

            // On va chercher une personne au hasard dans la liste des personnes créée au-dessus
            // Variante avec array_rand()
            $randomPerson = $personsList[array_rand($personsList)];
            $casting->setPerson($randomPerson);

            // On persist
            $manager->persist($casting);
        }

        // On flush
        $manager->flush();
    }
}
