<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, "/login");

        $client->submitForm("Se connecter", [
            "_username" => "admin",
            "_password" => "admin",
        ]);

        $client->followRedirect();
        $this->assertRouteSame("homepage");
    }

    public function testInvalidCredentials(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, "/login");
    
        $client->submitForm("Se connecter", [
            "_username" => "user",
            "_password" => "admin",
        ]);
    
        $client->followRedirect();

        $this->assertRouteSame("login");
    }
    
    public function testLogoutCheck(): void
    {
        $client = static::createClient();
        $client->request("GET", "/logout");

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}