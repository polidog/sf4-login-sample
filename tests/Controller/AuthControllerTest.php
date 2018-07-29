<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function test_showLoginForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('ログイン')->form();
        $form['_username'] = 'test@test.com';
        $form['_password'] = 'test';

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}