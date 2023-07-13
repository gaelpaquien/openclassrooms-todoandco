<?php

namespace AppBundle\Command;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTaskAuthorCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:update-task-author')
            ->setDescription('Mettre à jour les tâches qui n\'ont pas d\'auteur et définir l\'auteur sur l\'utilisateur anonyme')
            ->setHelp('Cette commande permet de mettre à jour les tâches qui n\'ont pas d\'auteur et définir l\'auteur sur l\'utilisateur anonyme');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $anonymousUser = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'anonymous@email.com']);

        if (!$anonymousUser) {
            $output->writeln('Aucun utilisateur anonyme trouvé');
            return 1; // Equivalent to returning Command::FAILURE
        }

        $tasks = $this->entityManager
            ->getRepository(Task::class)
            ->findBy(['author' => null]);

        foreach ($tasks as $task) {
            $task->setAuthor($anonymousUser);
        }

        $this->entityManager->flush();

        $output->writeln(count($tasks).' tâches ont été modifiées avec succès');

        return 0; // Equivalent to returning Command::SUCCESS
    }
}