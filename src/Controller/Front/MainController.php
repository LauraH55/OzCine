<?php

namespace App\Controller\Front;


use App\Entity\Movie;
use App\Form\ReviewType;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * Le paramètre de recherche est dans le paramètre GET ?search=xxx
     * 
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(MovieRepository $movieRepository, Request $request): Response
    {

        // Le paramètre GET à récupérer
        $search = $request->query->get('search');
        
        // Tous les films par ordre alphabétique
        // $movies = $movieRepository->findBy([], ['title' => 'ASC']);
        $movies = $movieRepository->findAllOrderedByTitleAsc($search);


        return $this->render('front/main/home.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Page d'un film
     * 
     * @Route("/movie/{id<\d+>}", name="movie_show", methods={"GET"})
     */
    public function movieShow(Movie $movie = null, CastingRepository $castingRepository)
    {
        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }
        
        // On peut également récupérer les castings depuis le contrôleur
        // plutôt que de laisser Doctrine le faire depuis Twig
        // Ici, on va chercher les objets de type Casting dont le film est $movie
        // $castings = $castingRepository->findBy(['movie' => $movie], ['creditOrder' => 'ASC']);
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
        // dump($castings);

        return $this->render('front/main/movie_show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
            
        ]);
    }

    /**
     * Page formulaire d'ajout d'une critique 
     * 
     * @todo Lier la critique à un film donné
     * 
     * @Route("/review/add", name="review_add", methods={"GET", "POST"})
     */
    public function addReview(Request $request): Response
    {
        // Création d'un formulaire d'ajout d'une review
        $form = $this->createForm(ReviewType::class, ['date' => new \DateTime()]);

        //2. Demande au formulaire d'inspecter la requête 
        $form->handleRequest($request);

        // Le formulaire est-il soumis et valide ? 
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les données du form
            $reviewData = $form->getData();

            // Fait quelque chose => se connecter
            dd($reviewData);
            

            // On redirige vers ....
            return $this->redirectToRoute('home');
        }

        return $this->render('front/main/form_review.html.twig', [
            // On envoie au template "une vue de formulaire" via createView()
            'form' => $form->createView(),
        ]);
    }

    /**
     * Déconnexion, utilise pour le logout
     * Cette méthode ne sera jamais appelée
     * car route intercepté par un évènement de Symfony
     * 
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
        dd('Ce code ne devrait jamais être exécuté...');
    }



}
