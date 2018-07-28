<?php

namespace App\Tests\Repository;


use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManager;
use Polidog\LoginSample\Account\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\KernelInterface;

class AccountRepositoryTest extends KernelTestCase
{
    /**
     * @var AccountRepository
     */
    private $repository;

    public static function setUpBeforeClass()
    {
        self::runCommand(self::bootKernel(), 'doctrine:fixtures:load --purge-with-truncate');
    }

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $registry = $kernel->getContainer()->get('doctrine');
        $this->repository = new AccountRepository($registry);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->repository = null;
    }


    public function testFindEmail()
    {
        $email = 'test@test.com';

        $account = $this->repository->findEmail($email);
        $this->assertInstanceOf(Account::class, $account);
        $this->assertSame($email, $account->getEmail());;
    }

    private static function runCommand(KernelInterface $kernel, string $command)
    {
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->run(new StringInput($command));
    }

}