<?php

namespace App\Tests\Form\Type;

use App\Entity\Task;
use App\Form\TaskType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'title',
            'content' => 'content',
        ];

        $model = new Task();

        $form = $this->factory->create(TaskType::class, $model);

        $expected = new Task();
        $expected->setTitle('title');
        $expected->setContent('content');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected->getTitle(), $model->getTitle());
        $this->assertEquals($expected->getContent(), $model->getContent());
    }
}
