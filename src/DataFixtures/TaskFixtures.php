<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 4; ++$i) {
            $this->createTaskWithAuthor($manager, 'Task User ' . 0 . ($i + 1), $i);
        }

        for ($i = 0; $i < 6; ++$i) {
            $this->createTaskWithoutAuthor($manager, 'Task Anonymous ' . 0 . ($i + 1));
        }

        $manager->flush();
    }

    public function createTaskWithAuthor(ObjectManager $manager, string $title, int $reference): void
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.');
        $task->setAuthor($this->getReference('user-' . $reference));
        $task->setIsDone(true);

        $manager->persist($task);
    }

    public function createTaskWithoutAuthor(ObjectManager $manager, string $title): void
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.');
        $task->setAuthor(null);
        $task->setIsDone(false);

        $manager->persist($task);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
