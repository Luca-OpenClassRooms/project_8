<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testGetId()
    {
        $this->assertEquals(null, $this->user->getId());
    }

    public function testGetUsername()
    {
        $this->user->setUsername('username');
        $this->assertEquals("username", $this->user->getUsername());
    }

    public function testGetPassword()
    {
        $this->user->setPassword('password');
        $this->assertEquals("password", $this->user->getPassword());
    }

    public function testGetEmail()
    {
        $this->user->setEmail('email');
        $this->assertEquals("email", $this->user->getEmail());
    }

    public function testGetRoles()
    {
        $this->user->setRoles(['ROLE_USER']);
        $this->assertEquals(["ROLE_USER"], $this->user->getRoles());
    }

    public function testEraseCredentials()
    {
        $this->assertEquals(null, $this->user->eraseCredentials());
    }

    public function testGetUserIdentifier()
    {
        $this->assertEquals(null, $this->user->getUserIdentifier());
    }
}
