<?php

namespace App\Tests\Fixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class FixturesTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Validate entity manager
        if (!$this->entityManager instanceof ObjectManager) {
            throw new \RuntimeException('The entity manager must implement ObjectManager interface.');
        }
    }

    public function testLoad(): void
    {
        // Set up the application and run the fixtures load command
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);

        // Check the output to verify the fixtures were loaded correctly
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        $this->assertCount(10, $tasks);
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $this->assertCount(7, $users);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
