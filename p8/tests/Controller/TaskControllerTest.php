<?php

namespace App\Tests\Controller;


use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Repository\TaskRepository;
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

        //And Check the redirect to task_list and check if the add Flash work
        $this->assertResponseStatusCodeSame(302,$client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/tasks');
        $crawler_redirect = $client->followRedirect();
        $this->assertSame(1, $crawler_redirect->filter('div.alert.alert-success')->count());

    }

    public function testEditTask()
    {
        //given Client
        $client = $this->createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));

        //When Get Request at /task_create
        $crawler = $client->request('GET','/tasks/23/edit');

        //then my controller have successful response get the form and put data
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Modifier')->form();

        // retrieve the Form object for the form belonging to this button and send form
        $client->submit($form,
        [
            'task_create[title]' => 'titre',
            'task_create[content]' => 'content',
        ]);



        //And Check the redirect to task_list and check if the add Flash work
        $this->assertResponseStatusCodeSame(302,$client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/tasks');
        $crawler_redirect = $client->followRedirect();
        $this->assertSame(1, $crawler_redirect->filter('div.alert.alert-success')->count());

    }

    public function testToogleAction()
    {
        //given Client And tasks not checked
        $client = $this->createClient();

        $taskRepository = static::getContainer()->get(TaskRepository::class);



        //When POST Request at /tasks/1/toggle
        $client->request('POST','/tasks/3/toggle');

        //And Call the task who passed in controller for test if the value are true
        $this->assertSame(true,$taskRepository->findOneBy(array('id' => 3))->getIsDone());

        //And Check the redirect to task_list
        $this->assertResponseStatusCodeSame(302,$client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/tasks');


    }

    public function testDeleteTask()
    {
        //Given Client
        $client = $this->createClient();
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        //When POST Request at /tasks/1/delete
        $client->request('POST', '/tasks/2/delete');

        //And Check the redirect to task_list and check if the add Flash work
        $this->assertResponseStatusCodeSame(302,$client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/tasks');
        $crawler_redirect = $client->followRedirect();
        $this->assertSame(1, $crawler_redirect->filter('div.alert.alert-success')->count());
    }

}
