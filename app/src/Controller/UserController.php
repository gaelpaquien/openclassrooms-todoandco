<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/users', name: 'user_list')]
    public function listAction(): Response
    {
        // Check if user is logged in
        if (!$this->userService->userIsLogged()) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');

            return $this->redirectToRoute('app_login');
        }

        // Check if user is admin
        if (!$this->userService->userIsAdmin($this->getUser())) {
            $this->addFlash('error', 'Vous ne pouvez pas accéder à cette page.');

            return $this->redirectToRoute('app_default');
        }

        return $this->render('user/list.html.twig', ['users' => $this->userService->list()]);
    }

    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if email already exists
            if ($this->userService->emailExist($form->get('email')->getData())) {
                $this->addFlash('error', 'Cette adresse email est déjà utilisée.');

                return $this->redirectToRoute('user_create');
            }

            $this->userService->create($form, $userPasswordHasher, $user);

            $this->addFlash('success', "Votre compte vient d'être créer avec succès.");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Check if the user is logged in
        if (!$this->userService->userIsLogged()) {
            $this->addFlash('error', 'Vous devez être connecté pour modifier un compte.');

            return $this->redirectToRoute('app_login');
        }

        // Check if user is the same as the logged in or if the user is admin
        if (!$this->userService->isSameUser($user) && !$this->userService->userIsAdmin($this->getUser())) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier ce compte.');

            return $this->redirectToRoute('app_default');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Check if email already exists and if it's not the same as the user's email
            if ($this->userService->emailTakenByAnotherUser($form->get('email')->getData(), $user)) {
                $this->addFlash('error', 'Cette adresse email est déjà utilisée.');

                return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
            }

            if ($form->isValid()) {
                $this->userService->edit($form, $userPasswordHasher, $user);
                $this->addFlash('success', 'Le compte a bien été modifié.');

                return $this->redirectToRoute('app_default');
            }
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
