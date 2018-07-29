<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\KernelInterface;

class SignupControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        self::runCommand(self::bootKernel(), 'doctrine:fixtures:load --purge-with-truncate');
    }

    public function test_RegisterUserAccountSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/signup/form');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('登録する')->form();
        $form['create_account[name]'] = 'test3';
        $form['create_account[email]'] = 'test3@test.com';
        $form['create_account[rawPassword][first]'] = 'test';
        $form['create_account[rawPassword][second]'] = 'test';

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function test_RegisterAccountFail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/signup/form');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('登録する')->form();
        $form['create_account[name]'] = 'test3';
        $form['create_account[email]'] = 'test3@test.com';
        $form['create_account[rawPassword][first]'] = 'test';
        $form['create_account[rawPassword][second]'] = '';

        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    private static function runCommand(KernelInterface $kernel, string $command)
    {
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->run(new StringInput($command));
    }
}