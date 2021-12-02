<?php

namespace App\Tests\Controller;


use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class TaskControllerTest extends WebTestCase
{

    public function testListTask(){
        //given client
        $client = $this->createClient();

        //When GET request at /task
        $client->request('GET','/tasks');

        //Then my controller return me list of Task
        $this->assertResponseIsSuccessful();
    }

    public function testCreateTask()
    {
        //given Client
        $client = $this->createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));


        //When Get Request at /task_create
        $crawler = $client->request('GET','/tasks_create');

        //then my controller have successful response
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Ajouter')->form();

        // retrieve the Form object for the form belonging to this button
        $client->submit($form,
        [
            'task_create[title]' => 'titre',
            'task_create[content]' => 'content',
        ]);


        $this->assertResponseStatusCodeSame(302,$client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/tasks');
    }

    public function testEditTask()
    {
        //given Client
        $client = $this->createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));

        //When Get Request at /task_create
        $crawler = $client->request('GET','/tasks/1/edit');

        //then my controller have successful response get the form and put data
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Modifier')->form();

        // retrieve the Form object for the form belonging to this button and send form
        $client->submit($form,
        [
            'task_create[title]' => 'titre',
            'task_create[content]' => 'content',
        ]);


        $this->assertResponseStatusCodeSame(302,$client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/tasks');

    }

}
