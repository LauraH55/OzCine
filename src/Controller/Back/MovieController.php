<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Repository\CastingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * Liste des films 
     * 
     * @Route("/admin", name="admin_home", methods={"GET"})
     */
    public function browse(MovieRepository $movieRepository, Request $request): Response
    {
        // List = bread 
        // Le paramètre GET à récupérer
        $search = $request->query->get('search');
        
        // Tous les films par ordre alphabétique
        // $movies = $movieRepository->findBy([], ['title' => 'ASC']);
        $movies = $movieRepository->findAllOrderedByTitleAsc($search);


        return $this->render('back/movie/browse.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Page d'un film
     * 
     * @Route("/admin/read/{id<\d+>}", name="admin_read", methods={"GET", "POST"})
     * 
     */
    public function read(Movie $movie, CastingRepository $castingRepository)
    {
        
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
        // dump($castings);

        return $this->render('back/movie/read.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
            
        ]);
    }

     /**
     * Ajout d'un film
     * Affichage + Traitement
     *
     * @Route("/admin/add", name="admin_add_movie", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {   
        // L'entité à créer
        $movie= new Movie();

        // Création d'un formulaire d'ajout d'un film
        // Le mapping se fait entre le formulaire et l'entité
        $form = $this->createForm(MovieType::class, $movie);

        //2. Demande au formulaire d'inspecter la requête 
        $form->handleRequest($request);

        // Le formulaire est-il soumis et valide ? 
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les données du form
            $reviewData = $form->getData();
            //dd($reviewData);

            // On demande au Manager de sauvegarder l'entité

            // SLUG
            $slug = $slugger->slug($movie->getTitle(), $separator = '-', $locale = null);
            $movie->setSlug($slug);
            
            $entityManager->persist($movie);
            $entityManager->flush();

            // On redirige vers ....
            return $this->redirectToRoute('admin_home');
        }

        return $this->render('back/movie/add.html.twig', [
            // On envoie au template "une vue de formulaire" via createView()
            'form' => $form->createView(),
        ]);

    }

     /**
     * Modification d'un film
     *
     *
     * @Route("/admin/edit/{id<\d+>}", name="admin_edit_movie", methods={"GET", "POST"})
     */
    public function edit(Movie $movie, Request $request): Response
    {   

         // 404 ?
         if (null === $movie) {
            // Dans notre cas, ce code ne sera jamais exécuté,
            // car la 404 est gérée par le ParamConverter
            // => voir delete() pour récupérer la main sur notre 404
            throw $this->createNotFoundException('Film non trouvé.');
        }
        
        // Création d'un formulaire d'ajout d'un film
        $form = $this->createForm(MovieType::class, $movie);

        //2. Demande au formulaire d'inspecter la requête 
        $form->handleRequest($request);

        // Le formulaire est-il soumis et valide ? 
        if ($form->isSubmitted() && $form->isValid()) {

            $movie->setUpdatedAt(new \DateTime());

            // On demande au Manager de sauvegarder l'entité
            $entityManager = $this->getDoctrine()->getManager();
            // Pas besoin de persist car on modifie
            $entityManager->flush();

            // On redirige vers ....
            return $this->redirectToRoute('admin_home', ['id' => $movie->getId()]);
        }

        return $this->render('back/movie/edit.html.twig', [
            // On envoie au template "une vue de formulaire" via createView()
            'movie' => $movie,
            'form' => $form->createView(),
        ]);

    }

    /**
     * Supprimer un film
     * 
     * ParamConverter => si $movie = null, alors notre contrôleur est exécuté
     * 
     * @Route("/admin/delete/{id<\d+>}", name="admin_delete_movie", methods={"DELETE"})
     */
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager)
    {
        // 404 ?
        // ParamConverter => si $movie = null, alors notre contrôleur est exécuté
        if (null === $movie) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // @see https://symfony.com/doc/current/security/csrf.html#generating-and-checking-csrf-tokens-manually
        // On réupère le nom du token qu'on a déposé dans le form
        $submittedToken = $request->request->get('token');

        // 'delete-movie' is the same value used in the template to generate the token
        if (! $this->isCsrfTokenValid('delete-movie', $submittedToken)) {

            throw $this->createAccessDeniedException('Action non autorisée.');
        }

         // Via l'injection, on peut utiliser directement $entityManager
         $entityManager->remove($movie);
         $entityManager->flush();
 
         return $this->redirectToRoute('admin_home');
    }

    
    
}
