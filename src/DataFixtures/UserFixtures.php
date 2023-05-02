<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setPassword('password'.$i);
            $user->setEmail("user$i@domain.fr");
            $user->setRoles(['ROLE_USER']);
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
