<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    public function testListOfUsersForAdmin(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("admin");
        $client->loginUser($user);

        $client->request("GET", "/users");
        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("admin");
        $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET, "/users/create");

        $this->assertInstanceOf(Form::class, $crawler->selectButton("Ajouter")->form());

        $client->submitForm("Ajouter", [
            "user[username]" => "test",
            "user[password][first]" => "test",
            "user[password][second]" => "test",
            "user[email]" => "test@gmail.com",
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        // $this->assertRouteSame("homepage");
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername("admin");
        $client->loginUser($user);
        $crawler = $client->request("GET", "/users/" . $user->getId() . "/edit");
        
        $this->assertInstanceOf(Form::class, $crawler->selectButton("Modifier")->form());
        
        $client->submitForm("Modifier", [
            "user[username]" => "test2",
            "user[password][first]" => "password",
            "user[password][second]" => "password",
            "user[email]" => "test@test.com",
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame("user_list");
    }
}
