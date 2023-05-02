<?php

namespace App\Tests\Form\Type;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Form\UserType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'username' => 'test',
            'password' => [
                "first" => "password",
                "second" => "password",
            ],
            'email' => 'test@test.com',
            'roles' => ['ROLE_USER'],
        ];

        $model = new User();

        $form = $this->factory->create(UserType::class, $model);

        $expected = new User();
        $expected->setUsername('test');
        $expected->setPassword('password');
        $expected->setEmail("test@test.com");
        $expected->setRoles(['ROLE_USER']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected->getUsername(), $model->getUsername());
        $this->assertEquals($expected->getPassword(), $model->getPassword());
        $this->assertEquals($expected->getEmail(), $model->getEmail());
        $this->assertEquals($expected->getRoles(), $model->getRoles());
    }
}
