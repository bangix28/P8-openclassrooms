<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('kenolane@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user,'root'))
            ->setUsername('kenolane');
        $manager->persist($user);
        $manager->flush();

        $task =  new Task();
        $task->setIsDone(false)
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setContent('content')
            ->setTitle('title')
            ->setUser($user);

        $manager->persist($task);
        $manager->flush();
    }
}
