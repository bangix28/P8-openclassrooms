<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserTest extends TestCase
{
    public function getEntity()
    {
        return (new user())
            ->setEmail('kenolane28@gmail.com')
            ->setPassword('test')
            ->setRoles(array('ROLE_ADMIN'))
            ->setUsername('kenolane');
    }

    public function assertHasErrors(User $user, $number = 0)
    {
        $errors = static::getContainer()->get(ValidatorInterface::class)->validate($user);
        $this->assertcount($number, $errors);
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(),0);
    }

    public function testInvalidEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('kenolane@gmail.com'),1);
        $this->assertHasErrors($this->getEntity()->setUsername(''),1);
        $this->assertHasErrors($this->getEntity()->setEmail(''),1);
        $this->assertHasErrors($this->getEntity()->setEmail('test'),1);
    }

}
