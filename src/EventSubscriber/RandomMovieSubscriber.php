<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment as Twig;

class RandomMovieSubscriber implements EventSubscriberInterface
{
    /**
     * On appelle le service MovieRepository
     *
     * 
     */
    private $movieRepository;

    /**
     * Twig
     */
    private $twig;

    public function __construct(MovieRepository $movieRepository, Twig $twig)
    {
        $this->movieRepository = $movieRepository;
        $this->twig = $twig;
    }
    
    public function onKernelController(ControllerEvent $event)
    {
        // Parfois (par ex. lors des exceptions : 404 etc)
        // le contrôleur n'est pas un tableau

        $controller = $event->getController();

        if (is_array($controller)) {
            // Récupérons le contrôleur, qui se trouve à l'index 0 du tableau
            // qui contient le contrôleur et la méthode à appeler
            $controller = $controller[0];
        }


        // On récupère le nom de la classe de contrôleur
        $controllerClassName = (get_class($controller));

        // 1. On limite l'exécution de cette méthode
        // à certaines parties de l'application
        // Par exemple pour les contrôleurs de notre appli, soit qui commencent par
        // 'App\Controller'
        if (strpos($controllerClassName, 'App\Controller') === false) {
            // On sort du suscriber
            return;
        }

       
        // dump('RandomMovieSubscriber appelé.');

        // 2. On va chercher un film au hasard
        // @todo Utiliser Order By Rand() LIMIT 1 dans une requête custom dans le Repository
        // En attendant, on va faire un shuffle() sur tous les films
        $movies = $this->movieRepository->findAll();
        // On mélange, on prend le premier
        shuffle($movies);
        $randomMovie = $movies[0];
        //dump($randomMovie);


        // 3. On le transmet à Twig
        $this->twig->addGlobal('randomMovie', $randomMovie);




    }
    /**
     * Indique les évènements auxquels ce subscriber souscrit
     * et la méthode à appeler pour chaque type d'évènements
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
