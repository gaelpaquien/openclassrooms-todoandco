<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();

        // Check if the user is logged in
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour créer une tâche.');

            return $this->redirectToRoute('task_list');
        }

        // Set the author on the task
        $task->setAuthor($this->getUser());

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        // Check if the user is the author of the task
        if ($this->getUser() !== $task->getAuthor()) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier une tâche dont vous n\'êtes pas l\'auteur.');

            return $this->redirectToRoute('task_list');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $anonymousAuthor = $em->getRepository(User::class)->findOneBy(['email' => 'anonymous@email.com']);

        // If task is not anonymous, check if the user is the author of the task
        if ($task->getAuthor()->getEmail() !== $anonymousAuthor->getEmail()) {
            if ($this->getUser()->getId() !== $task->getAuthor()->getId()) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer une tâche dont vous n\'êtes pas l\'auteur.');

                return $this->redirectToRoute('task_list');
            }
        }

        // If author task is anonymous, check if the user is admin
        if ($task->getAuthor()->getEmail() === $anonymousAuthor->getEmail()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Seul un administrateur peut supprimer une tâche dont l\'auteur est anonyme.');

                return $this->redirectToRoute('task_list');
            }
        }

        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
