<?php

namespace App\Service;

use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository, private EntityManagerInterface $em, private Security $security)
    {
        $this->taskRepository = $taskRepository;
        $this->em = $em;
        $this->security = $security;
    }

    public function userIsAuthor($task): bool
    {
        return $this->security->getUser() === $task->getAuthor();
    }

    public function authorIsAnonymous($task): bool
    {
        return $task->getAuthor()->getEmail() === 'anonymous@email.com';
    }

    public function list(): array
    {
        return $this->taskRepository->findAll();
    }

    public function create($task): void
    {
        // Set the author on the task
        $task->setAuthor($this->security->getUser());

        $this->em->persist($task);
        $this->em->flush();
    }

    public function edit(): void
    {
        $this->em->flush();
    }

    public function toggle($task): void
    {
        $task->toggle(!$task->isDone());

        $this->em->flush();
    }

    public function delete($task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }

}