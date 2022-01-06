<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TaskTest extends KernelTestCase
{


    public function getEntity()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        return (new task())
            ->setCreatedAt(new \DateTime('2020-01-01 23:30:30'))
            ->setContent('content')
            ->setIsDone(true)
            ->setUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')))
            ->setTitle('title');
    }

    public function assertHasErrors(Task $task,$number = 0)
    {
        $errors = static::getContainer()->get(ValidatorInterface::class)->validate($task);
        $this->assertcount($number, $errors);
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(),0);
    }
    public function testInvalidEntity(){
        $this->assertHasErrors($this->getEntity()->setContent(''),1);
        $this->assertHasErrors($this->getEntity()->setTitle(''),1);
    }

    public function toogleAction(Task $task, $expected)
    {
        $task->setIsDone(!$task->getIsDone());
        $this->assertSame($expected,$task->getIsDone());
    }

    public function testToogleAction()
    {
        $this->toogleAction($this->getEntity(), false);
        $this->toogleAction($this->getEntity()->setIsDone(false), true);
    }
}
