<?php

namespace App\Tests\Controller;


use App\Entity\LoginUser;
use Polidog\LoginSample\Account\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class TopControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        self::runCommand(self::bootKernel(), 'doctrine:fixtures:load --purge-with-truncate');
    }

    /**
     * @var Client
     */
    protected $client = null;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->login();
    }

    public function tearDown()
    {
        unset($this->client);
        parent::tearDown();
    }

    public function test_ShowTopPage()
    {
        $this->client->request('GET','/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    /**
     * @param $firewall
     * @param UserInterface $user
     * @param $name
     */
    protected function login()
    {
        $repository = $this->client->getContainer()->get('doctrine')->getRepository(Account::class);
        $account = $repository->findOneBy(['email' => 'test@test.com']);
        $user = new LoginUser($account);

        $token = new UsernamePasswordToken($user, null, 'account', $user->getRoles());

        $session = $this->client->getContainer()->get('session');
        $session->set('_security_account', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private static function runCommand(KernelInterface $kernel, string $command)
    {
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->run(new StringInput($command));
    }
}