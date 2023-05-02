<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;

class TaskControllerTest extends WebTestCase
{
    public function testListTask(): void
    {
        $client = static::createClient();
        $client->request("GET", "/tasks");

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame("login");
    }

    public function testCreateTask(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("user");
        $client->loginUser($user);
        $crawler = $client->request("GET", "/tasks/create");

        $this->assertInstanceOf(Form::class, $crawler->selectButton("Ajouter")->form());
        
        $client->submitForm("Ajouter", [
            "task[title]" => "New Task title",
            "task[content]" => "New task content",
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame("task_list");
    }

    public function testEditTask()
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("user");

        $client->loginUser($user);
        $task = $user->getTasks()->first();
        $client->request("GET", "/tasks/" . $task->getId() . "/edit");
        
        $this->assertRouteSame("task_edit");
        $this->assertRequestAttributeValueSame("id", $task->getId());
        $this->assertInputValueSame("task[title]", $task->getTitle());
        $this->assertSelectorTextSame("textarea[name='task[content]']", $task->getContent());
        $this->assertRouteSame("task_edit");
        $client->submitForm("Modifier", [
            "task[title]" => "essai",
            "task[content]" => "essai",
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame("task_list");
    }

    public function testDeleteTask()
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("user");

        $client->loginUser($user);
        $task = $user->getTasks()->first();
        $client->request("GET", "/tasks/" . $task->getId() . "/delete");
        
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame("task_list");
        $this->assertSelectorExists("div.alert.alert-success");
    }

    public function testTaskToggle(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("user");
        
        $client->loginUser($user);
        $task = $user->getTasks()->first();

        $client->request("GET", "/tasks/". $task->getId() ."/toggle");
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertRouteSame("task_list");
        $this->assertSelectorExists("div.alert.alert-success");
    }
}