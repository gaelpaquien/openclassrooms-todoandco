<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskService $taskService, private UserService $userService)
    {
        $this->taskService = $taskService;
        $this->userService = $userService;
    }

    #[Route('/tasks', name: 'task_list')]
    public function listAction(): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->taskService->list()]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request): Response
    {
        // Check if the user is logged in
        if (!$this->userService->userIsLogged()) {
            $this->addFlash('error', 'Vous devez être connecté pour créer une tâche.');

            return $this->redirectToRoute('app_login');
        }

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->create($task);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request): Response
    {
        // Check if the user is logged in
        if (!$this->userService->userIsLogged()) {
            $this->addFlash('error', 'Vous devez être connecté pour modifier une tâche.');

            return $this->redirectToRoute('app_login');
        }

        // If task author is not anonymous, check if the user is the author of the task
        if (!$this->taskService->authorIsAnonymous($task) && !$this->taskService->userIsAuthor($task)) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier une tâche dont vous n\'êtes pas l\'auteur.');

            return $this->redirectToRoute('task_list');
        }

        // If task author is anonymous, check if the user is admin
        if ($this->taskService->authorIsAnonymous($task) && !$this->userService->userIsAdmin($this->getUser())) {
            $this->addFlash('error', 'Seul un administrateur peut modifier une tâche anonyme.');

            return $this->redirectToRoute('task_list');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->edit();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task): Response
    {
        // Check if the user is logged in
        if (!$this->userService->userIsLogged()) {
            $this->addFlash('error', 'Vous devez être connecté pour modifier l\'état d\'une tâche.');

            return $this->redirectToRoute('app_login');
        }

        // If task author is not anonymous, check if the user is the author of the task
        if (!$this->taskService->authorIsAnonymous($task) && !$this->taskService->userIsAuthor($task)) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier l\'état d\'une tâche dont vous n\'êtes pas l\'auteur.');

            return $this->redirectToRoute('task_list');
        }

        // If task author is anonymous, check if the user is admin
        if ($this->taskService->authorIsAnonymous($task) && !$this->userService->userIsAdmin($this->getUser())) {
            $this->addFlash('error', 'Seul un administrateur peut modifier l\'état d\'une tâche dont l\'auteur est anonyme.');

            return $this->redirectToRoute('task_list');
        }

        $this->taskService->toggle($task);

        if ($task->isDone()) {
            $this->addFlash('success', 'La tâche a bien été marquée comme terminée.');
        }

        if (!$task->isDone()) {
            $this->addFlash('success', 'La tâche a bien été marquée comme non terminée.');
        }

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task): Response
    {
        // Check if the user is logged in
        if (!$this->userService->userIsLogged()) {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer une tâche.');

            return $this->redirectToRoute('app_login');
        }

        // If task author is not anonymous, check if the user is the author of the task
        if (!$this->taskService->authorIsAnonymous($task) && !$this->taskService->userIsAuthor($task)) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer une tâche dont vous n\'êtes pas l\'auteur.');

            return $this->redirectToRoute('task_list');
        }

        // If task author is anonymous, check if the user is admin
        if ($this->taskService->authorIsAnonymous($task) && !$this->userService->userIsAdmin($this->getUser())) {
            $this->addFlash('error', 'Seul un administrateur peut supprimer une tâche dont l\'auteur est anonyme.');

            return $this->redirectToRoute('task_list');
        }

        $this->taskService->delete($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
