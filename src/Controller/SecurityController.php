<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        
        // Quand on est en POST
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Affiche le formulaure de login avec les éventuelles erreurs
        // et le nom saisi précédemment
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Déconnexion, utile pour le logout
     * Cette méthode ne sera jamais appelée
     * car route interceptée par un événement de Symfony
     * 
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Cette méthode va être appelée si l'authentification est valide
     * et que l'utilisateur a été connecté par le système de sécurité
     * 
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function apiLogin()
    {   
        // A ce stade, l'utilisateur est considéré comme connecté sur le système
        // On va retourner au front, ce que l'on souhaite
        // A adapter selon nos besoins
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }
}
