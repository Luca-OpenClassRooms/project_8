<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    protected function setUp(): void
    {
        $this->task = new Task();
    }

    public function testGetId()
    {
        $this->assertEquals(null, $this->task->getId());
    }

    public function testGetUser()
    {
        $user = new User();
        $this->task->setUser($user);
        $this->assertEquals($user, $this->task->getUser());
    }

    public function testGetCreatedAt()
    {
        $this->assertNotNull($this->task->getCreatedAt());
    }

    public function testGetTitle()
    {
        $this->task->setTitle('title');
        $this->assertEquals("title", $this->task->getTitle());
    }

    public function testGetContent()
    {
        $this->task->setContent('content');
        $this->assertEquals("content", $this->task->getContent());
    }

    public function testIsDone()
    {
        $this->task->setIsDone(true);
        $this->assertEquals(true, $this->task->isDone());
    }

    public function testToggle()
    {
        $status = $this->task->isDone();
        $this->task->toggle(!$status);
        $this->assertEquals(!$status, $this->task->isDone());
    }

    public function testSetCreatedAt()
    {
        $date = new \DateTimeImmutable();
        $this->task->setCreatedAt($date);
        $this->assertEquals($date, $this->task->getCreatedAt());
    }
}
