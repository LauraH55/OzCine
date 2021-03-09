<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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

            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, Response::HTTP_CONFLICT);

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
     * Delete movie
     * @Route("/api/movies/{id<\d+>}", name="api_movies_delete", methods="DELETE")
     */
    public function delete(EntityManagerInterface $entityManager, MovieRepository $movieRepository, $id)
    {   
        // Récupère l'id du film a supprimé
        $movie = $movieRepository->find($id);

        // Condition : si le film est différent de null alors on le supprime
        if ($movie !== null) {

            $entityManager->remove($movie);
            $entityManager->flush();

            return $this->json('Film supprimé', 200);

        } else {

            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Film non trouvé.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }
    }
}

