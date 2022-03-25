<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function testRegistrationUser()
    {
        //given not connected Client
        $client = $this->createClient();

        //WHEN USER send Request at login whith correct Information and roles ADMIN
        $crawler = $client->request('GET', '/register');
        //then my controller have successful response
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Ajouter')->form();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the Form object for the form belonging to this button
        $client->submit($form,
            [
                'registration_form[email]' => 'kenolane28@gmail.com',
                'registration_form[plainPassword]' => 'root123456',
                'registration_form[username]' => 'kenolane',
                'registration_form[roles]' => 'ROLE_USER'

            ]);

        //check if user are registered
        $user = $userRepository->findOneBy(array('email' => 'kenolane28@gmail.com'));
        $this->assertNotEmpty($user);
        $this->assertSame(array('ROLE_USER'), $user->getRoles());

        //check if redirect are good
        $this->assertResponseRedirects('/login');
    }


    public function testEditUserAdmin()
    {
        //given not connected Client
        $client = $this->createClient();
        //CONNECT ADMIN client
        $userRepository = static::getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));

        //WHEN USER send Request at login whith correct Information and roles ADMIN
        $crawler = $client->request('GET', '/user/edit/1');
        //then my controller have successful response
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Modifier')->form();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the Form object for the form belonging to this button
        $client->submit($form,
            [
                'user_edit[email]' => 'kenolane28@gmail.com',
                'user_edit[Username]' => 'kénolane',
                'user_edit[roles]' => 'ROLE_USER'

            ]);

        //check if user are modified
        $user = $userRepository->findOneBy(array('email' => 'kenolane28@gmail.com'));
        $this->assertSame('kénolane', $user->getUsername());
        $this->assertSame('kenolane28@gmail.com', $user->getEmail());
        $this->assertSame('ROLE_USER', $user->getRoles()[0]);

        //check if redirect are good
        $this->assertResponseRedirects('/users');
    }


    public function testEditUserNotAdmin()
    {
        //given not connected Client
        $client = $this->createClient();

        //WHEN USER send Request at login whith correct Information and roles ADMIN
        $client->request('GET', '/user/edit/1');

        //check if redirect are good
        $this->assertResponseRedirects('/login');

    }

    public function testListUserNotAdmin()
    {
        //given client
        $client = $this->createClient();

        //When GET request at /users
        $client->request('GET', '/users');

        //check if redirect are good
        $this->assertResponseRedirects('/login');

    }

    public function testListUserAdmin()
    {
        //given client
        $client = $this->createClient();

        //connect Admin
        $userRepository = static::getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));

        //When GET request at /users
        $client->request('GET', '/users');

        //Then my controller return me list of user
        $this->assertResponseIsSuccessful();

    }




}