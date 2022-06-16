<?php


namespace App\Tests;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
Use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase
{

    public function loginUser($client)
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        return $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));
    }

    public function setUp(): void
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $fixture = $this->getContainer()->get(AppFixtures::class);
        $fixture->load($em);
        self::ensureKernelShutdown();
    }

    public function tearDown(): void
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 0');
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(2);
        $purger->purge();
        $em->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 1');
    }

}