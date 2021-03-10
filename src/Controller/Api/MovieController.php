<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * API movies
 */
class MovieController extends AbstractController
{
    /**
     * Read all movies
     * @Route("/api/movies", name="api_movies_read", methods="GET")
     */
    public function read(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        // Le 4ème argument représente le "contexte"
        // qui sera transmis au Serializer
        return $this->json($movies, 200, [], ['groups' => 'movies_read']);
    }

    /**
     * Read one movie
     * @Route("/api/movies/{id<\d+>}", name="api_movies_read_item", methods="GET")
     */
    public function readItem(Movie $movie = null): Response
    {
        // 404 ?
        if ($movie === null) {

            // Optionnel, message pour le front
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Film non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        // @todo Tenter d'utiliser la requête custom
        // de jointure sur castings et persons

        // Le 4ème argument représente le "contexte"
        // qui sera transmis au Serializer
        return $this->json($movie, 200, [], ['groups' => [
            'movies_read',
            'movies_read_item',
            ]
        ]);
    }

    /**
     * Create movie
     * ON a besoin de REquest et du Serialize
     * @Route("/api/movies", name="api_movies_create", methods="POST")
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        // Récupérer le contenu de la requête, c'est-à-dire le JSON
        $jsonContent = $request->getContent();
        //dd($jsonContent);

        // On désérialise ce JSON en entité Movie, grâce au Serializer
        // On transforme le JSON qui est du text en objet de type App\Entity\Movie
        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-an-object

        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        //dd($movie);

        // @todo Valider l'entité => gestion affiche des erreurs en JSON

        $errors = $validator->validate($movie);
        //dd($errors);


        if (count($errors) > 0) {

            // Le tableau des erreurs est retourné sous forme de JSON
            // Avec un statut erreur 422
            // @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);

        }

        // On sauvegarde le film (if submitted is valid...)
        // On sauvegarde le film
        $entityManager->persist($movie);
        $entityManager->flush();
        //dd($movie);

    

        // On redirige vers movies_read_item
        return $this->redirectToRoute(
            'api_movies_read_item',
            ['id' => $movie->getId()],
            // C'est cool d'utiliser les constantes de classe !
            // => ça aide à la lecture du code et au fait de penser objet
            Response::HTTP_CREATED
        );

    }

    /**
     * Edit movie (PUT et PATCH)
     * 
     * @Route("/api/movies/{id<\d+>}", name="api_movies_put", methods={"PUT"})
     * @Route("/api/movies/{id<\d+>}", name="api_movies_patch", methods={"PATCH"})
     */
    public function putAndPatch(Movie $movie = null, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        // 1. On souhaite modifier le film dont l'id est transmis via l'URL

        // 404 ?
        if ($movie === null) {
            // On retourne un message JSON + un statut 404
            return $this->json(['error' => 'Film non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Notre JSON qui se trouve dans le body
        $jsonContent = $request->getContent();

        // @todo Pour PUT, s'assurer qu'on ait un certain nombre de champs
        // @todo Pour PATCH, s'assurer qu'on ait au moins un champ
        // Sinon => 422 HTTP_UNPROCESSABLE_ENTITY

        // 2. On va devoir associer les données JSON reçues sur l'entité existante
        // On désérialise les données reçues depuis le front ($request->getContent())... 
        // ... dans l'objet Movie à modifier
        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-in-an-existing-object
        $serializer->deserialize(
            $jsonContent,
            Movie::class,
            'json',
            // On a cet argument en plus qui indique au serializer quelle entité existante modifier
            [AbstractNormalizer::OBJECT_TO_POPULATE => $movie]
        );

        // Validation de l'entité désérialisée
        $errors = $validator->validate($movie);
        // Génération des erreurs
        if (count($errors) > 0) {
            // On retourne le tableau d'erreurs en Json au front avec un status code 422
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // On flush $movie qui a été modifiée par le Serializer
        $em->flush();

        // @todo Conditionner le message de retour au cas où 
        // l'entité ne serait pas modifiée
        return $this->json(['message' => 'Film modifié.'], Response::HTTP_OK);
    }

     /**
     * Delete movie
     * 
     * @Route("/api/movies/{id<\d+>}", name="api_movies_delete", methods="DELETE")
     */
    public function delete(Movie $movie = null, EntityManagerInterface $entityManager)
    {
        // 404
        if ($movie === null) {

            // Optionnel, message pour le front
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Film non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }


        // Sinon on supprime le film en base
        $entityManager->remove($movie);
        $entityManager->flush();

        // L'objet $movie existe toujours en mémoire PHP jusqu'à la fin du script
        return $this->json(
            ['message' => 'Le film ' . $movie->getTitle() . ' a été supprimé !'],
            Response::HTTP_OK);
    }


}

