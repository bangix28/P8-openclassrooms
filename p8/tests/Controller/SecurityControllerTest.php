<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\TestCase;

class SecurityControllerTest extends TestCase
{

    public function loginUser($client)
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        return $client->loginUser($userRepository->findOneBy(array('email' => 'kenolane@gmail.com')));
    }

    public function testLoginAnonymousFail()
    {
        //given not connected Client
        $client = $this->createClient();

        //WHEN USER send Request at login whith incorrect Information
        $crawler = $client->request('GET', '/login');
        //then my controller have successful response
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Sign in')->form();

        // retrieve the Form object for the form belonging to this button
        $client->submit($form,
            [
                'email' => 'fail@gmail.com',
                'password' => 'fail',
            ]);

        $this->assertResponseRedirects('/login');
        $crawler_redirect = $client->followRedirect();
        $this->assertSame(1, $crawler_redirect->filter('div.alert.alert-danger')->count());


    }

    public function testLoginAnonymous()
    {
        //given not connected Client
        $client = $this->createClient();

        //WHEN USER send Request at login whith correct Information
        $crawler = $client->request('GET', '/login');
        //then my controller have successful response
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Sign in')->form();

        // retrieve the Form object for the form belonging to this button
        $client->submit($form,
            [
                'email' => 'kenolane@gmail.com',
                'password' => 'root',
            ]);

        $this->assertResponseRedirects('/tasks');


    }

    public function testLoginUser()
    {
        //given not connected Client
        $client = $this->createClient();
        $this->loginUser($client);


        //WHEN USER send Request at login whith correct Information
        $crawler = $client->request('GET', '/login');


        //check if the redirect are good
        $this->assertResponseRedirects('/tasks');


    }

    public function testLogout(){
        //given connected client
        $client = $this->createClient();
        $this->loginUser($client);
        //WHEN user send a logout request
        $client->request('GET','/logout');

        //check if the redirect are good
        $this->assertResponseRedirects();


    }

}