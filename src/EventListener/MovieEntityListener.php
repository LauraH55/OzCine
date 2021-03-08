<?php


namespace App\EventListener;

use App\Entity\Movie;
use App\Service\MySlugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Cet écouteur va être appelé selon la configuration de services.yaml
 * ici avant chaque persist() et update()
 * 
 * On remplace l'appel au Slugger présent dans Fixtures, Movie Add et Edit
 * par cet écouteur unique
 */
class MovieEntityListener
{
    private $mySlugger;

    public function __construct(MySlugger $mySlugger)
    {
        $this->mySlugger = $mySlugger;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function updateSlug(Movie $movie, LifecycleEventArgs $event): void
    {
        // Slugifie le film
        $slug = $this->mySlugger->slugify($movie->getTitle());
        $movie->setSlug($slug);
    }
}
