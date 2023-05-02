<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername('user')
            ->setEmail("user@domain.com")
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('user'));

        $manager->persist($user);
        
        $admin = (new User())
            ->setUsername('admin')
            ->setEmail('admin@domain.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('admin'));
        
            $manager->persist($admin);

        $anonymousTask = (new Task())
            ->setTitle('Anonymous Task')
            ->setContent('Tâche anonyme.');
        
        $manager->persist($anonymousTask);

        $taskUser = (new Task())
            ->setTitle('Task User')
            ->setContent('Tâche de l\'utilisateur.');

        $taskUser->setUser($user);

        $manager->persist($taskUser);

        $taskAdmin = (new Task())
            ->setTitle('Task Admin')
            ->setContent('Tâche de l\'administrateur.');

        $taskAdmin->setUser($admin);

        $manager->persist($taskAdmin);

        $manager->flush();
    }
}
