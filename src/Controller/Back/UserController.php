<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserAddType;
use App\Form\UserEditType;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;
use App\Service\MessageGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * ! Préfixe de route + ! Préfixe de nom de route
 * 
 * @Route("/back/user", name="back_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/browse", name="user_browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('back/user/browse.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="user_add", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder,MessageGenerator $messageGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(UserAddType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // C'est là qu'on encode le mot de passe du User (qui se trouve dans $user)
            $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            // On réassigne le mot de passe encodé dans le User
            $user->setPassword($hashedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Flash
            $this->addFlash('success', $messageGenerator->getHappyMessage());

            return $this->redirectToRoute('back_user_browse');
        }

        return $this->render('back/user/add.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read/{id}", name="user_read", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('back/user/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger, MessageGenerator $messageGenerator): Response
    {   
        $form = $this->createForm(UserEditType::class, $user);
        // Le mot de passe du $user existant va être écrasé par $request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si mot de passe du form n'est pas vide
            // c'est qu'on veut le changer
            if ($form->get('password')->getData() !== '') {
                // C'est là qu'on encode le mot de passe du User (qui se trouve dans $user)
                $hashedPassword = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
                // On réassigne le mot passe encodé dans le User
                $user->setPassword($hashedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            // Let's log !
            $logger->info('User modifié', [
                'user' => $user->getUsername(),
                'by' => $this->getUser()->getUsername(),
            ]);

            // Flash
            $this->addFlash('success', $messageGenerator->getHappyMessage());

            return $this->redirectToRoute('back_user_browse');
        }

        return $this->render('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_user_browse');
    }
}
