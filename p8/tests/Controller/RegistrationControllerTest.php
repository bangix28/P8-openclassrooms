<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\TestCase;

class RegistrationControllerTest extends TestCase
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
        $this->assertSame(array('ROLE_USER'),$user->getRoles());

        //check if redirect are good
        $this->assertResponseRedirects('/login');
    }

    public function testRegistrationAdmin()
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
                'registration_form[roles]' => 'ROLE_USER',

            ]);

        //check if user are registered
        $user = $userRepository->findOneBy(array('email' => 'kenolane28@gmail.com'));
        $this->assertNotEmpty($user);
        $this->assertSame(array('ROLE_USER'),$user->getRoles());

        //check if redirect are good
        $this->assertResponseRedirects('/login');
    }


}