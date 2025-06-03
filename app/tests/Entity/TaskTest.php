<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;

class TaskTest extends TestCase
{
    public function testGetId()
    {
        $task = new Task();
        $this->assertNull($task->getId());
    }

    public function testGetSetTitle()
    {
        $task = new Task();
        $task->setTitle('Task test');
        $this->assertEquals('Task test', $task->getTitle());
    }

    public function testGetSetContent()
    {
        $task = new Task();
        $task->setContent('Content test');
        $this->assertEquals('Content test', $task->getContent());
    }

    public function testGetSetCreatedAt()
    {
        $task = new Task();
        $task->setCreatedAt(new DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $task->getCreatedAt());
    }

    public function testGetSetIsDone()
    {
        $task = new Task();
        $task->setIsDone(true);
        $this->assertEquals(true, $task->isDone());
    }

    public function testGetSetAuthor()
    {
        $task = new Task();
        $user = new User();
        $task->setAuthor($user);
        $this->assertEquals($user, $task->getAuthor());
    }
}
