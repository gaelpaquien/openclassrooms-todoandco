<?php 

namespace App\Tests\Command;

use App\Command\UpdateTaskAuthorCommand;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateTaskAuthorCommandTest extends TestCase
{
    public function testUpdateTasksWithAnonymousUser()
    {
        $anonymousUser = $this->createMock(User::class);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn($anonymousUser);

        $tasks = [
            new Task(), // Utilisez simplement une instance concrète de Task
            new Task()
        ];

        $taskRepository = $this->createMock(TaskRepository::class);
        $taskRepository->method('findBy')->willReturn($tasks);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');

        $command = new UpdateTaskAuthorCommand($entityManager, $taskRepository, $userRepository);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertStringContainsString('2 tâches ont été modifiées avec succès', $commandTester->getDisplay());
    }

    public function testNoAnonymousUserFound()
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn(null); // Retourner null pour simuler qu'aucun utilisateur n'a été trouvé

        $taskRepository = $this->createMock(TaskRepository::class);
        $taskRepository->method('findBy')->willReturn([]); // Pas nécessaire, mais pour garder la cohérence

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('flush'); // Le flush ne doit jamais être appelé dans ce scénario

        $command = new UpdateTaskAuthorCommand($entityManager, $taskRepository, $userRepository);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertStringContainsString('Aucun utilisateur anonyme trouvé', $commandTester->getDisplay());
        $this->assertSame(1, $commandTester->getStatusCode()); // Vérifier que la commande retourne une erreur
    }

}
